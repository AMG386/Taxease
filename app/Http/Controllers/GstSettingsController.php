<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GstProfile;

class GstSettingsController extends Controller
{
    public function edit()
    {
        $profile = GstProfile::firstOrNew(['user_id'=>auth()->id() ?? 1]);
        return view('gst.settings', compact('profile'));
    }

    public function update(Request $req)
    {
        $data = $req->validate([
            'gstin' => 'nullable|string|max:15',
            'gst_type' => 'required|in:regular,composition',
            'business_type' => 'nullable|string|max:50',
            'composition_rate' => 'nullable|numeric|min:0|max:99.99',
        ]);
        $data['user_id'] = auth()->id() ?? 1;
        GstProfile::updateOrCreate(['user_id'=>$data['user_id']], $data);
        return back()->with('ok','GST settings saved');
    }
}
