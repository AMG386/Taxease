<?php

namespace App\Http\Controllers;

use App\Models\GstFiling;
use App\Services\GstService;
use Illuminate\Http\Request;

class GstFilingController extends Controller
{
    public function stepper() { return view('gst.stepper'); }

    public function summary(Request $request, GstService $svc)
    {
        $request->validate(['period'=>'required|date_format:Y-m']);
        return response()->json($svc->monthlySummary(auth()->id() ?? 1, $request->period));
    }

    public function generateGstr3b(Request $request, GstService $svc)
    {
        $request->validate(['period'=>'required|date_format:Y-m']);
        $summary = $svc->monthlySummary(auth()->id() ?? 1, $request->period);
        $payload = $svc->gstr3bPayload($summary);

        $filing = GstFiling::updateOrCreate(
            ['user_id'=>auth()->id() ?? 1, 'filing_type'=>'GSTR3B', 'period'=>$request->period],
            ['payload'=>$payload, 'status'=>'draft', 'total_payable'=>$summary['payable']]
        );

        return response()->json(['ok'=>true,'payload'=>$payload,'filing_id'=>$filing->id]);
    }

    public function markFiled(Request $request)
    {
        $request->validate(['filing_id'=>'required|integer']);
        $f = GstFiling::findOrFail($request->filing_id);
        $f->status = 'filed';
        $f->filed_at = now();
        $f->save();
        return response()->json(['ok'=>true,'message'=>'Marked as filed']);
    }
}
