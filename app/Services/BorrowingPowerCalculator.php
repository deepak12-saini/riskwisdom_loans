<?php

namespace App\Services;

class BorrowingPowerCalculator
{
    /**
     * @return array{
     *     income: float,
     *     expenses: float,
     *     deposit: float,
     *     rate: float,
     *     term_years: int,
     *     monthly_capacity: float,
     *     loan_amount: float,
     *     low: int,
     *     high: int,
     *     range_label: string
     * }
     */
    public function estimate(
        float $income,
        float $expenses,
        float $deposit,
        float $ratePercent,
        int $termYears,
    ): array {
        $rate = $ratePercent / 100 / 12;
        $termMonths = max(1, $termYears) * 12;
        $monthlyCapacity = max(0, ($income / 12) * 0.3 - $expenses);

        if ($rate > 0) {
            $loanAmount = $monthlyCapacity * ((1 - pow(1 + $rate, -$termMonths)) / $rate);
        } else {
            $loanAmount = $monthlyCapacity * $termMonths;
        }

        $low = max(0, (int) (round(($loanAmount + $deposit) * 0.9 / 1000) * 1000));
        $high = max(0, (int) (round(($loanAmount + $deposit) * 1.05 / 1000) * 1000));

        return [
            'income' => $income,
            'expenses' => $expenses,
            'deposit' => $deposit,
            'rate' => $ratePercent,
            'term_years' => $termYears,
            'monthly_capacity' => round($monthlyCapacity, 2),
            'loan_amount' => round($loanAmount, 2),
            'low' => $low,
            'high' => $high,
            'range_label' => sprintf(
                '$%s – $%s (purchase price guide)',
                number_format($low),
                number_format($high),
            ),
        ];
    }
}
