<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        $rows = Expense::where('user_id', auth()->id() ?? 1)
            ->orderByDesc('date')
            ->get();

        return view('itr.expenses', compact('rows'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'        => 'required|date',
            'category'    => 'required|string',
            'ref_no'      => 'nullable|string',
            'amount'      => 'required|numeric',
            'itc_claimed' => 'nullable|boolean',
        ]);

        $data['user_id'] = auth()->id() ?? 1;
        $data['itc_claimed'] = (bool)($data['itc_claimed'] ?? false);

        Expense::create($data);

        return back()->with('ok', 'Expense added successfully.');
    }
}
