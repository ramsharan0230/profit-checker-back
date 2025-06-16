<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Report;
use App\Services\ExportReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ExportReportController extends Controller
{
    public function __construct(protected ExportReportService $reportService) {}

    public function exportReport(ReportRequest $request)
    {
        $validated = $request->validated();
        $validated['ip_address'] = $request->ip();
        $reportType = $validated['reportType'];

        if (!in_array($reportType, ['pdf', 'csv'])) {
            throw new \InvalidArgumentException('Invalid report type.');
        }

        return $this->reportService->generate($validated, $reportType);
    }


    public function reports(Request $request) : JsonResponse 
    {
        $reports = Report::where('ip_address', $request->ip())->get();
        return ApiResponse::success($reports, 'Reports fetched successfully.', Response::HTTP_OK);    
    }
}