<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ItrService;
use App\Models\ItrProfile;

class ItrController extends Controller
{
    public function summary(Request $req, ItrService $svc)
    {
        $req->validate(['fy' => 'required|regex:/^\d{4}-\d{2}$/']);
        $userId = auth()->id() ?? 1;
        $pl = $svc->computePL($userId, $req->fy);
        if ($req->wantsJson()) return response()->json($pl);
        return view('itr.summary', compact('pl'));
    }

    public function exportJson(Request $req, ItrService $svc)
    {
        $req->validate(['fy' => 'required|regex:/^\d{4}-\d{2}$/']);
        $userId = auth()->id() ?? 1;
        $pl = $svc->computePL($userId, $req->fy);
        $profile = ItrProfile::where('user_id',$userId)->first()?->toArray() ?? [];
        $json = $svc->toItrJson($pl, $profile);
        return response()->json($json);
    }
}
