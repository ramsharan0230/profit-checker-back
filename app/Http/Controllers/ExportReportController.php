<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExportReportService;

class ExportReportController extends Controller
{
    protected ExportReportService $reportService;

    public function __construct(ExportReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function exportReport(Request $request)
    {
        $reportType = $request->input('reportType');

        if (!in_array($reportType, ['pdf', 'csv'])) {
            throw new \InvalidArgumentException('Invalid report type.');
        }

        return $this->reportService->generate($request->all(), $reportType);
    }
}
