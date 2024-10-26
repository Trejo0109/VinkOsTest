<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Services\IntegrateVisitorInformationService;

class IntegrateVisitorInformationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $files = Storage::files('vinkOs/archivosVisitas');

        IntegrateVisitorInformationService::integrateInformation($files);



        $this->assertTrue(true);
    }
}
