<?php

namespace App\Http\Controllers;

use App\Services\GstService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function pdf(Request $request, GstService $svc)
    {
        $request->validate(['period'=>'required|date_format:Y-m']);
        $summary = $svc->monthlySummary(auth()->id() ?? 1, $request->period);
        $html = view('reports.gst-summary-pdf', compact('summary'))->render();
        return Pdf::loadHTML($html)->download("GST-Summary-{$summary['period']}.pdf");
    }

    public function csv(Request $request, GstService $svc)
    {
        $request->validate(['period'=>'required|date_format:Y-m']);
        $summary = $svc->monthlySummary(auth()->id() ?? 1, $request->period);

        $response = new StreamedResponse(function () use ($summary) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Metric','Value']);
            fputcsv($out, ['Period', $summary['period']]);
            fputcsv($out, ['Sales Tax', $summary['sales_tax']]);
            fputcsv($out, ['ITC', $summary['itc']]);
            fputcsv($out, ['Payable', $summary['payable']]);
            fclose($out);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=GST-Summary-{$summary['period']}.csv");
        return $response;
    }
}
