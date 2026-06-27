<?php

namespace App\Services;

class StampDutyCalculator
{
    /**
     * @return array{
     *     state: string,
     *     state_label: string,
     *     property_value: float,
     *     first_home_buyer: bool,
     *     base_duty: float,
     *     duty: float,
     *     mortgage_registration_fee: float,
     *     total_government_charges: float,
     *     fhb_note: string|null
     * }
     */
    public function estimate(string $state, float $propertyValue, bool $firstHomeBuyer = false): array
    {
        $states = config('stamp_duty.states', []);
        $stateConfig = $states[$state] ?? null;

        if ($stateConfig === null) {
            throw new \InvalidArgumentException("Unsupported state: {$state}");
        }

        $propertyValue = max(0, $propertyValue);
        $baseDuty = $this->calculateProgressive($propertyValue, $stateConfig['brackets']);
        $duty = $baseDuty;
        $fhbNote = null;

        if ($firstHomeBuyer && isset($stateConfig['fhb'])) {
            [$duty, $fhbNote] = $this->applyFirstHomeBuyerConcession(
                $propertyValue,
                $baseDuty,
                $stateConfig['fhb'],
            );
        }

        $mortgageFee = (float) config('stamp_duty.mortgage_registration_fee', 187);

        return [
            'state' => $state,
            'state_label' => (string) ($stateConfig['label'] ?? $state),
            'property_value' => $propertyValue,
            'first_home_buyer' => $firstHomeBuyer,
            'base_duty' => round($baseDuty, 2),
            'duty' => round(max(0, $duty), 2),
            'mortgage_registration_fee' => $mortgageFee,
            'total_government_charges' => round(max(0, $duty) + $mortgageFee, 2),
            'fhb_note' => $fhbNote,
        ];
    }

    /**
     * @param  array<int, array{max: int|null, rate: float}>  $brackets
     */
    private function calculateProgressive(float $value, array $brackets): float
    {
        if ($value <= 0) {
            return 0;
        }

        $duty = 0.0;
        $previousMax = 0.0;

        foreach ($brackets as $bracket) {
            $max = $bracket['max'] ?? null;
            $rate = (float) $bracket['rate'];

            if ($max === null) {
                $taxable = $value - $previousMax;
                if ($taxable > 0) {
                    $duty += $taxable * $rate;
                }
                break;
            }

            if ($value <= $previousMax) {
                break;
            }

            $taxable = min($value, (float) $max) - $previousMax;
            if ($taxable > 0) {
                $duty += $taxable * $rate;
            }

            $previousMax = (float) $max;
        }

        return $duty;
    }

    /**
     * @param  array{full_exemption_up_to?: int, partial_exemption_up_to?: int}  $fhb
     * @return array{0: float, 1: string|null}
     */
    private function applyFirstHomeBuyerConcession(float $value, float $baseDuty, array $fhb): array
    {
        $fullExemption = (float) ($fhb['full_exemption_up_to'] ?? 0);
        $partialExemption = (float) ($fhb['partial_exemption_up_to'] ?? $fullExemption);

        if ($fullExemption > 0 && $value <= $fullExemption) {
            return [0, 'First home buyer concession applied — full exemption on this estimate.'];
        }

        if ($partialExemption > $fullExemption && $value <= $partialExemption) {
            $factor = ($partialExemption - $value) / ($partialExemption - $fullExemption);
            $factor = max(0, min(1, $factor));

            return [
                $baseDuty * $factor,
                'First home buyer concession applied — partial exemption on this estimate.',
            ];
        }

        return [$baseDuty, null];
    }
}
