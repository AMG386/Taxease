<?php
namespace App\Http\Controllers;

use App\Models\GstReturn;
use App\Models\GstReturnAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class GstReturnAuditController extends Controller
{
    public function store(Request $request, GstReturn $gstReturn)
    {
        // Optional: policy/authorization
        $this->authorize('update', $gstReturn); // or your own method

        $data = $request->validate([
            'file'    => 'required|file|mimes:pdf,xlsx,xls,csv,zip,txt,doc,docx|max:20480', // 20MB
            'remarks' => 'nullable|string|max:255',
        ]);

        $file = $data['file'];
        $path = $file->store('gst_audits'); // storage/app/gst_audits/...

        $audit = $gstReturn->audits()->create([
            'uploaded_by'   => auth()->id(),
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
            'size'          => $file->getSize(),
            'mime'          => $file->getClientMimeType(),
            'remarks'       => $data['remarks'] ?? null,
        ]);

        return back()->with('ok', 'Audit / Working uploaded successfully.');
    }

    public function download(GstReturnAudit $audit)
    {
        $gstReturn = $audit->gstReturn;

        // Authorization: viewer must be allowed to view that return
        $this->authorize('view', $gstReturn); // or your authorizeView($gstReturn)

        if (!Storage::exists($audit->file_path)) {
            return back()->withErrors(['file' => 'File not found on server.']);
        }
        return Storage::download($audit->file_path, $audit->original_name);
    }

    public function destroy(GstReturnAudit $audit)
    {
        $gstReturn = $audit->gstReturn;
        $this->authorize('update', $gstReturn);

        // Delete file then record
        Storage::delete($audit->file_path);
        $audit->delete();

        return back()->with('ok', 'Audit file deleted.');
    }
}
