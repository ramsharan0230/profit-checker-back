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
            return response()->json(['error' => 'Invalid report type.'], 400);
        }

        return $this->reportService->generate($request->all(), $reportType);
    }
}
