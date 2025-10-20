<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;

class IncomeController extends Controller
{
    public function index()
    {
        $rows = Income::where('user_id', auth()->id() ?? 1)
            ->orderByDesc('date')
            ->get();

        return view('itr.incomes', compact('rows'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'      => 'required|date',
            'head'      => 'required|string',
            'sub_head'  => 'nullable|string',
            'ref_no'    => 'nullable|string',
            'amount'    => 'required|numeric',
        ]);

        $data['user_id'] = auth()->id() ?? 1;
        Income::create($data);

        return back()->with('ok', 'Income added successfully.');
    }
}
