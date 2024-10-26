<?php

namespace App\Services;

use App\Repositories\StatisticsRepository;
use App\Repositories\VisitorRepository;
use App\Validators\VisitFileValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class IntegrateService
{

    public static function integrateInformation($files, $disk = 'sftp')
    {
        foreach ($files as  $file) {
            self::processFile($file, $disk);
            $path = self::backupFile($file);
            self::zipBackupFiles($path);
        }
    }

    private static function processFile($file, $disk)
    {
        $content = Storage::disk($disk)->get($file);

        $lines = explode(PHP_EOL, $content);
        $headers = str_getcsv(array_shift($lines));

        if (!VisitFileValidator::layout($headers)) {
            Log::error("{$file}: El formato del layout no es el requerido.");
            return;
        }
        $data = [];
        $errors = [];
        foreach ($lines as $key => $line) {
            if (trim($line) === '') continue;
            $record = self::mapData(str_getcsv($line));

            $validator = VisitFileValidator::visitor($record);
            if ($validator->fails()) {
                $errors[] = $record;
                continue;
            }
            $data[] = $record;
        }

        StatisticsRepository::insert($data);
        VisitorRepository::getOrInsert($data);
    }

    private static function backupFile(string $file)
    {
        $currentDate = Carbon::now()->format('Ymd');

        $path = 'home/etl/visitas/bckp/' . $currentDate;

        Storage::copy($file, $path . '/' . basename($file));
        return $path;
        // Storage::disk($disk)->delete($file);
    }

    protected static function zipBackupFiles($path)
    {
        $backupDir = storage_path('app/' . $path);
        $zipFileName = storage_path('app/' . $path . '.zip');

        $zip = new ZipArchive();
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = scandir($backupDir);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $zip->addFile("$backupDir/$file", $file);
                }
            }

            $zip->close();
            File::deleteDirectory($backupDir);
        }
    }

    private static function mapData(array $visitor)
    {
        $data =  [
            'email' => $visitor[0],
            'jyv' => $visitor[1],
            'badmail' => $visitor[2],
            'baja' => $visitor[3],
            'fecha_envio' => $visitor[4],
            'fecha_open' => $visitor[5],
            'opens' => $visitor[6],
            'opens_virales' => $visitor[7],
            'fecha_click' => $visitor[8],
            'clicks' => $visitor[9],
            'clicks_virales' => $visitor[10],
            'links' => $visitor[11],
            'ips' => $visitor[12],
            'navegadores' => $visitor[13],
            'plataformas' => $visitor[14],
        ];
        return $data;
    }
}
