<?php

namespace App\Console\Commands;

use App\Services\IntegrateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class IntegrateInformationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integrate:visits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que carga archivos desde un servidor mediante SFTP y los procesa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->processData();
    }

    protected function processData()
    {
        $files = Storage::disk('sftp')->files('vinkOs/archivosVisitas');
        IntegrateService::integrateInformation($files);
        $this->info('La informacion se ha integrado correctamente.');
    }
}
