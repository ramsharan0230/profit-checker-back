<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ExportReportControllerTest extends TestCase
{
    /**
     * A basic integration test for generating pdf report.
     */
    public function test_export_report_pdf()
    {
        $payload = [
            'selectedItems' => [
                ['selectedProduct' => 'Product X', 'quantity' => 1, 'cost' => 100, 'sell' => 200]
            ],
            'laborHours' => 2,
            'laborCost' => 50,
            'fixedOverheads' => 20,
            'targetMargin' => 30,
            'reportType' => 'pdf'
        ];

        $response = $this->post('/api/report/export-quote-summary', $payload);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }


    /**
     * A basic integration test for generating csv report.
     */
    public function test_export_report_csv()
    {
        $payload = [
            'selectedItems' => [
                ['selectedProduct' => 'Product Y', 'quantity' => 2, 'cost' => 50, 'sell' => 80],
            ],
            'laborHours' => 5,
            'laborCost' => 20,
            'fixedOverheads' => 10,
            'targetMargin' => 25,
            'reportType' => 'csv',
        ];

        $response = $this->post('/api/report/export-quote-summary', $payload);

        $response->assertStatus(200);
        $this->assertStringStartsWith('text/csv', $response->headers->get('Content-Type'));
        $response->assertSee('Product Y');
    }

    public function test_invalid_report_type_returns_error()
    {
        $payload = [
            'reportType' => 'unknown'
        ];

        $response = $this->post('/api/report/export-quote-summary', $payload);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid report type.']);
    }
}
