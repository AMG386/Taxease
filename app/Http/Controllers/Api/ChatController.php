<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $q = strtolower(trim($request->input('q','')));
        if ($q === '') return response()->json(['answer'=>'Please type a tax question.']);

        $faq = [
            'due date' => 'Usual GSTR-3B due date: 20th of next month (may vary by turnover/state).',
            'gstr-1'  => 'GSTR-1 is monthly/quarterly outward supplies summary filed before GSTR-3B.',
            'itc'     => 'Input Tax Credit can offset output tax if invoice is in GSTR-2A/2B and goods/services received.',
        ];
        foreach($faq as $k=>$v) if (str_contains($q,$k)) return response()->json(['answer'=>$v]);

        return response()->json(['answer'=>'Try: “How to file GSTR-1 for Oct?” or “What is ITC?”']);
    }
}
