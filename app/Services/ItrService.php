<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Expense;
use Carbon\Carbon;

class ItrService
{
    public function computePL(int $userId, string $fy): array
    {
        // fy format: 2024-25 -> from 2024-04-01 to 2025-03-31
        [$startYear, $endShort] = explode('-', $fy);
        $from = Carbon::createMidnightDate((int)$startYear, 4, 1);
        $to   = $from->copy()->addYear()->subDay();

        $incomes = Income::where('user_id',$userId)->whereBetween('date', [$from,$to])->get();
        $expenses= Expense::where('user_id',$userId)->whereBetween('date', [$from,$to])->get();

        $incomeTotal  = (float) $incomes->sum('amount');
        $expenseTotal = (float) $expenses->sum('amount');
        $profit = $incomeTotal - $expenseTotal;

        $byHead = $incomes->groupBy('head')->map->sum('amount');
        $byCat  = $expenses->groupBy('category')->map->sum('amount');

        return [
            'fy' => $fy,
            'income_total' => round($incomeTotal,2),
            'expense_total' => round($expenseTotal,2),
            'profit' => round($profit,2),
            'income_by_head' => $byHead,
            'expense_by_category' => $byCat,
        ];
    }

    public function toItrJson(array $pl, array $profile=[]): array
    {
        // Minimal illustrative JSON, not official portal schema
        return [
            'profile' => [
                'pan' => $profile['pan'] ?? null,
                'name' => $profile['full_name'] ?? null,
                'assessment_year' => $profile['assessment_year'] ?? null,
            ],
            'financial_year' => $pl['fy'],
            'totals' => [
                'gross_receipts' => $pl['income_total'],
                'total_expenses' => $pl['expense_total'],
                'profit' => $pl['profit'],
            ],
            'breakup' => [
                'income_by_head' => $pl['income_by_head'],
                'expense_by_category' => $pl['expense_by_category'],
            ],
        ];
    }
}
