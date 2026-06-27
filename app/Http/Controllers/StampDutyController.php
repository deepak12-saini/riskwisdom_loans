<?php

namespace App\Http\Controllers;

use App\Services\StampDutyCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StampDutyController extends Controller
{
    public function __construct(
        private readonly StampDutyCalculator $calculator,
    ) {}

    public function calculate(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'state' => ['required', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.states')))],
            'property_value' => ['required', 'numeric', 'min:0', 'max:50000000'],
            'first_home_buyer' => ['nullable', 'boolean'],
        ], [
            'state.required' => 'Please select your state or territory.',
            'property_value.required' => 'Please enter the property purchase price.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('tools.stamp-duty')
                ->withErrors($validator)
                ->withInput()
                ->withFragment('sd-calculator');
        }

        $validated = $validator->validated();

        $result = $this->calculator->estimate(
            (string) $validated['state'],
            (float) $validated['property_value'],
            $request->boolean('first_home_buyer'),
        );

        return redirect()
            ->route('tools.stamp-duty')
            ->with('stamp_duty_result', $result)
            ->withInput()
            ->withFragment('sd-result');
    }
}
