<?php

namespace App\Support\Tax;

class GstMath
{
    public static function splitTax(
        float $taxable, float $rate, string $placeOfSupply, string $originState
    ): array {
        $totalTax = round($taxable * $rate / 100, 2);

        // Intra-state: CGST + SGST; Inter-state: IGST
        if (mb_strtoupper($placeOfSupply) === mb_strtoupper($originState)) {
            $cgst = round($totalTax / 2, 2);
            $sgst = $totalTax - $cgst;
            return ['cgst' => $cgst, 'sgst' => $sgst, 'igst' => 0.00, 'total' => $totalTax];
        }
        return ['cgst' => 0.00, 'sgst' => 0.00, 'igst' => $totalTax, 'total' => $totalTax];
    }

    public static function sum(array $rows, array $cols): array
    {
        $out = [];
        foreach ($cols as $c) $out[$c] = 0;
        foreach ($rows as $r) foreach ($cols as $c) $out[$c] += (float)($r[$c] ?? 0);
        foreach ($cols as $c) $out[$c] = round($out[$c], 2);
        return $out;
    }
}
