<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GstProfile;
use App\Http\Requests\GstSettingsRequest;
use App\Http\Resources\GstProfileResource;

class GstSettingsController extends Controller
{
    /**
     * Display the GST settings form.
     */
    public function edit()
    {
        $profile = GstProfile::firstOrNew(['user_id' => auth()->id() ?? 1]);
        return view('gst.settings', compact('profile'));
    }

    /**
     * Update or create GST settings.
     */
    public function update(GstSettingsRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id() ?? 1;

        // Create or update the GST profile
        $profile = GstProfile::updateOrCreate(
            ['user_id' => $data['user_id']], 
            $data
        );

        return back()->with('ok', 'GST settings saved successfully!');
    }

    /**
     * Display a specific GST profile (for API or detailed view).
     */
    public function show()
    {
        $profile = GstProfile::where('user_id', auth()->id() ?? 1)->first();
        
        if (!$profile) {
            return response()->json(['error' => 'GST profile not found'], 404);
        }

        return new GstProfileResource($profile);
    }

    /**
     * Delete GST profile.
     */
    public function destroy()
    {
        $profile = GstProfile::where('user_id', auth()->id() ?? 1)->first();
        
        if (!$profile) {
            return back()->with('error', 'GST profile not found');
        }

        $profile->delete();
        
        return back()->with('ok', 'GST profile deleted successfully!');
    }
}
