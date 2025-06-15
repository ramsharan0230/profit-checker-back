<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportRequestValidationTest extends TestCase
{
    /**
     * Test that validation fails with missing required fields.
     */
    public function test_missing_required_fields_returns_validation_error()
    {
        $response = $this->postJson('/api/report/export-quote-summary', []);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Validation failed',
        ]);
        $response->assertJsonValidationErrors([
            'reportType',
            'selectedItems',
            'laborHours',
            'laborCost',
            'fixedOverheads',
            'targetMargin',
        ]);
    }

    /**
     * Test invalid reportType value.
     */
    public function test_invalid_report_type_returns_error()
    {
        $payload = [
            'reportType' => 'docx', // invalid
            'selectedItems' => [
                [
                    'selectedProduct' => 'ABC',
                    'quantity' => 1,
                    'cost' => 100,
                    'sell' => 200,
                ],
            ],
            'laborHours' => 5,
            'laborCost' => 20,
            'fixedOverheads' => 10,
            'targetMargin' => 30,
        ];

        $response = $this->postJson('/api/report/export-quote-summary', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['reportType']);
    }

    /**
     * Test negative cost or quantity.
     */
    public function test_negative_values_return_validation_error()
    {
        $payload = [
            'reportType' => 'pdf',
            'selectedItems' => [
                [
                    'selectedProduct' => 'Item X',
                    'quantity' => -1,
                    'cost' => -50,
                    'sell' => 100,
                ],
            ],
            'laborHours' => -3,
            'laborCost' => -10,
            'fixedOverheads' => -5,
            'targetMargin' => -20,
        ];

        $response = $this->postJson('/api/report/export-quote-summary', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'selectedItems.0.quantity',
            'selectedItems.0.cost',
            'laborHours',
            'laborCost',
            'fixedOverheads',
            'targetMargin',
        ]);
    }

    /**
     * Test successful validation.
     */
    public function test_valid_request_passes_validation()
    {
        $payload = [
            'reportType' => 'csv',
            'selectedItems' => [
                [
                    'selectedProduct' => 'Product X',
                    'quantity' => 1,
                    'cost' => 100,
                    'sell' => 200,
                ],
            ],
            'laborHours' => 2,
            'laborCost' => 50,
            'fixedOverheads' => 20,
            'targetMargin' => 30,
        ];

        $response = $this->postJson('/api/report/export-quote-summary', $payload);
        $response->assertStatus(200);
    }
}
