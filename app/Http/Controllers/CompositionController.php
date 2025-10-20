<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\CompositionService;

class CompositionController extends Controller
{
    public function dashboard(Request $req, CompositionService $svc)
    {
        $fy = $req->get('fy', '2025-26');
        $q  = $req->get('q', 'Q1'); // Q1..Q4
        $userId = auth()->id() ?? 1;

        $data = $svc->computeQuarter($fy, $q, $userId);
        if ($req->wantsJson()) return response()->json($data);

        return view('gst.composition_dashboard', compact('data','fy','q'));
    }

    public function exportJson(Request $req, CompositionService $svc)
    {
        $fy = $req->get('fy', '2025-26');
        $q  = $req->get('q', 'Q1');
        $userId = auth()->id() ?? 1;

        $summary = $svc->computeQuarter($fy, $q, $userId);
        $json = $svc->toGstr4Json($summary);
        return response()->json($json);
    }
}
