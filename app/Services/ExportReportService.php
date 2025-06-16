<?php

namespace App\Services;

use App\Events\SaveReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;

class ExportReportService
{
    public function generate(array $data, string $reportType): Response
    {
        $summary = $this->prepareSummaryData($data);
        $summary['aiSuggestion'] = $data['ai_suggestions'] ?? '';
        $summary['ip_address'] = $data['ip_address'] ?? '';

        return match ($reportType) {
            'pdf' => $this->generatePdf($summary),
            'csv' => $this->generateCsv($summary),
            default => response()->json(['error' => 'Invalid report type.'], 400),
        };
    }

    private function generatePdf(array $data): Response
    {
        $pdf = Pdf::loadView('exports.summary', $data);
        $filename = 'summary-' . now()->format('Ymd_His') . '.pdf';

        $reportData['filename'] = $filename;
        $reportData['ipAddress'] = $data['ip_address'];
        $reportData['reportType'] = 'pdf';

        event(new SaveReport($reportData));

        return $pdf->download($filename);
    }

    private function generateCsv(array $data): Response
    {
        $csv = Writer::createFromString('');
        $csv->insertOne(['Product', 'Quantity', 'Cost (£)', 'Sell (£)']);

        foreach ($data['items'] as $item) {
            $csv->insertOne([
                $item['selectedProduct'],
                $item['quantity'],
                number_format($item['cost'], 2),
                number_format($item['sell'], 2),
            ]);
        }

        $csv->insertOne([]);
        $csv->insertOne(['Total Quantity', $data['totalQty']]);
        $csv->insertOne(['Total Cost (£)', number_format($data['totalCost'], 2)]);
        $csv->insertOne(['Total Sell (£)', number_format($data['totalSell'], 2)]);

        $csv->insertOne([]);
        $csv->insertOne(['Labor Hours', $data['laborHours']]);
        $csv->insertOne(['Labor Cost/Hour (£)', number_format($data['laborCost'], 2)]);
        $csv->insertOne(['Total Labor Cost (£)', number_format($data['totalLaborCost'], 2)]);
        $csv->insertOne(['Fixed Overheads (£)', number_format($data['fixedOverheads'], 2)]);
        $csv->insertOne(['Labor & Overhead (£)', number_format($data['laborAndOverhead'], 2)]);

        $csv->insertOne([]);
        $csv->insertOne(['Gross Profit (£)', number_format($data['grossProfit'], 2)]);
        $csv->insertOne(['Margin (%)', number_format($data['margin'], 2)]);
        $csv->insertOne(['Target Margin (%)', number_format($data['targetMargin'], 2)]);

        $csv->insertOne([]);
        $csv->insertOne(['AI Suggestions']);
        $csv->insertOne([preg_replace("/[\r\n]+/", " ", $data['aiSuggestion'])]);

        $filename = 'quote-summary-' . now()->format('Ymd_His') . '.csv';

        $reportData['filename'] = $filename;
        $reportData['ipAddress'] = $data['ip_address'];
        $reportData['reportType'] = 'csv';

        event(new SaveReport($reportData));

        return response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    private function prepareSummaryData(array $data): array
    {
        $items = $data['selectedItems'] ?? [];

        $totalQty = 0;
        $totalCost = 0;
        $totalSell = 0;

        foreach ($items as $item) {
            $qty = $item['quantity'];
            $cost = $item['cost'];
            $sell = $item['sell'];

            $totalQty += $qty;
            $totalCost += $qty * $cost;
            $totalSell += $qty * $sell;
        }

        $laborHours = $data['laborHours'] ?? 0;
        $laborCost = $data['laborCost'] ?? 0;
        $fixedOverheads = $data['fixedOverheads'] ?? 0;
        $targetMargin = $data['targetMargin'] ?? 0;

        $totalLaborCost = $laborHours * $laborCost;
        $laborAndOverhead = $totalLaborCost + $fixedOverheads;
        $grossProfit = $totalSell - $totalCost - $laborAndOverhead;
        $margin = $totalSell > 0 ? ($grossProfit / $totalSell) * 100 : 0;

        return [
            'items' => $items,
            'totalQty' => $totalQty,
            'totalCost' => $totalCost,
            'totalSell' => $totalSell,
            'laborHours' => $laborHours,
            'laborCost' => $laborCost,
            'totalLaborCost' => $totalLaborCost,
            'fixedOverheads' => $fixedOverheads,
            'laborAndOverhead' => $laborAndOverhead,
            'grossProfit' => $grossProfit,
            'margin' => $margin,
            'targetMargin' => $targetMargin,
        ];
    }
}
