<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;

class ReminderController extends Controller
{
    public function index()
    {
        $rows = Reminder::where('user_id', auth()->id() ?? 1)
            ->orderBy('due_at')->get();
        return view('reminders.index', compact('rows'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'title' => 'required|string',
            'message' => 'nullable|string',
            'due_at' => 'required|date',
            'channel' => 'nullable|string'
        ]);
        $data['user_id'] = auth()->id() ?? 1;
        Reminder::create($data);
        return back()->with('ok','Reminder created');
    }

    public function delete(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);
        $reminder->delete();
        return back()->with('ok','Deleted');
    }
}
