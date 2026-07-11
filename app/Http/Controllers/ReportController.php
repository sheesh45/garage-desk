<?php

namespace App\Http\Controllers;

use App\Exports\RevenueReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function revenue(): BinaryFileResponse
    {
        return Excel::download(new RevenueReportExport(), 'revenue-report.xlsx');
    }
}
