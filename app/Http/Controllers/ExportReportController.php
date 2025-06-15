<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Services\ExportReportService;
use Illuminate\Support\Facades\Log;

class ExportReportController extends Controller
{
    public function __construct(protected ExportReportService $reportService) {}

    public function exportReport(ReportRequest $request)
    {

        $validated = $request->validated();
        $reportType = $validated['reportType'];

        if (!in_array($reportType, ['pdf', 'csv'])) {
            throw new \InvalidArgumentException('Invalid report type.');
        }

        return $this->reportService->generate($validated, $reportType);
    }
}