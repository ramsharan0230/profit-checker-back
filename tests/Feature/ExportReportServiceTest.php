<?php

namespace Tests\Feature;

use App\Services\ExportReportService;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Tests\TestCase;

class ExportReportServiceTest extends TestCase
{
    protected array $trialData;

    protected function setUp():void
    {
        parent::setUp();

         $this->trialData = [
            'selectedItems' => [
                ['selectedProduct' => 'Product A', 'quantity' => 2, 'cost' => 10, 'sell' => 25],
                ['selectedProduct' => 'Product B', 'quantity' => 1, 'cost' => 20, 'sell' => 40],
            ],
            'laborHours' => 5,
            'laborCost' => 15,
            'fixedOverheads' => 30,
            'targetMargin' => 30,
        ];
    }


    /**
     * A basic test for generating pdf report and download it.
     */
    public function test_generate_pdf_returns_download_response()
    {
        View::shouldReceive('make')->andReturn('rendered');
        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturnSelf()
            ->shouldReceive('download')
            ->once()
            ->andReturn(new HttpResponse('PDF content'));

        $service = new ExportReportService();
        $response = $service->generate($this->trialData, 'pdf');

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals('PDF content', $response->getContent());
    }


    /**
     * A basic test for generating csv report and download it.
     */
    public function test_generate_csv_returns_csv_response()
    {
        $service = new ExportReportService();
        $response = $service->generate($this->trialData, 'csv');

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Product A', $response->getContent());
        $this->assertStringContainsString('Gross Profit', $response->getContent());
    }


    public function test_invalid_report_type_returns_error()
    {
        $service = new ExportReportService();
        $response = $service->generate($this->trialData, 'invalid-type');

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Invalid report type.']),
            $response->getContent()
        );
    }
}
