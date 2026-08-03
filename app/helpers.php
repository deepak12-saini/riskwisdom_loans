<?php

if (! function_exists('business_address_line')) {
    function business_address_line(): string
    {
        $address = config('riskwisdom.address', []);

        $line1 = trim((string) ($address['line1'] ?? ''));
        $suburb = trim((string) ($address['suburb'] ?? ''));
        $state = trim((string) ($address['state'] ?? ''));
        $postcode = trim((string) ($address['postcode'] ?? ''));

        if ($line1 === '' || $suburb === '') {
            return '';
        }

        $locality = $suburb;

        if ($state !== '' && $postcode !== '') {
            $locality .= ' '.$state.' '.$postcode;
        } elseif ($state !== '') {
            $locality .= ' '.$state;
        } elseif ($postcode !== '') {
            $locality .= ' '.$postcode;
        }

        return $line1.', '.$locality;
    }
}

if (! function_exists('business_address_schema')) {
    /**
     * @return array<string, mixed>|null
     */
    function business_address_schema(): ?array
    {
        $address = config('riskwisdom.address', []);

        $line1 = trim((string) ($address['line1'] ?? ''));
        $suburb = trim((string) ($address['suburb'] ?? ''));

        if ($line1 === '' || $suburb === '') {
            return null;
        }

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $line1,
            'addressLocality' => $suburb,
            'addressRegion' => (string) ($address['state'] ?? ''),
            'postalCode' => (string) ($address['postcode'] ?? ''),
            'addressCountry' => (string) ($address['country'] ?? 'AU'),
        ];
    }
}

if (! function_exists('contact_url')) {
    function contact_url(?string $intent = null, array $extra = []): string
    {
        $params = $extra;

        if ($intent !== null && $intent !== '') {
            $params['intent'] = $intent;
        }

        $query = $params !== [] ? '?'.http_build_query($params) : '';

        return route('home').$query.'#contact';
    }
}

if (! function_exists('rate_review_url')) {
    function rate_review_url(): string
    {
        return route('rate-review');
    }
}

if (! function_exists('calendly_url')) {
    function calendly_url(): ?string
    {
        $url = trim((string) config('riskwisdom.calendly_url', ''));

        return $url !== '' ? $url : null;
    }
}

if (! function_exists('calendly_embed_url')) {
    /**
     * Calendly inline embed URL with branding + hide duplicate cookie banner
     * (site should manage cookies; Calendly still loads inside iframe).
     *
     * @see https://calendly.com/help/how-to-customize-your-embed
     */
    function calendly_embed_url(): ?string
    {
        $base = calendly_url();

        if ($base === null) {
            return null;
        }

        $params = [
            'hide_gdpr_banner' => '1',
            'hide_event_type_details' => '1',
            'background_color' => 'ffffff',
            'text_color' => '12263d',
            'primary_color' => '1b63c8',
        ];

        $separator = str_contains($base, '?') ? '&' : '?';

        return $base.$separator.http_build_query($params);
    }
}

if (! function_exists('calendly_hide_branding')) {
    /**
     * Whether to pass branding: false to Calendly embed JS.
     * Requires Calendly Standard+ and "Use Calendly branding" off in account settings.
     */
    function calendly_hide_branding(): bool
    {
        return filter_var(config('riskwisdom.calendly_hide_branding', true), FILTER_VALIDATE_BOOL);
    }
}

if (! function_exists('signing_configured')) {
    function signing_configured(): bool
    {
        return app(\App\Services\DocumentSigningManager::class)->active()->isConfigured();
    }
}

if (! function_exists('signing_provider_label')) {
    function signing_provider_label(): string
    {
        return app(\App\Services\DocumentSigningManager::class)->active()->providerLabel();
    }
}

if (! function_exists('docusign_configured')) {
    function docusign_configured(): bool
    {
        return app(\App\Services\DocuSignService::class)->isConfigured();
    }
}

if (! function_exists('ad_landing_url')) {
    /**
     * Build a landing page URL with UTM parameters for paid campaigns.
     *
     * @param  array<string, string>  $utm
     */
    function ad_landing_url(string $page, array $utm = []): string
    {
        $routeConfig = config('riskwisdom.ad_landing_pages.'.$page);

        if ($routeConfig === null || $routeConfig === '') {
            return route('home');
        }

        if (is_array($routeConfig)) {
            [$routeName, $params] = $routeConfig;
            $baseUrl = route($routeName, $params);
        } else {
            $baseUrl = route($routeConfig);
        }

        $defaults = [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => $page,
        ];

        $query = array_filter(array_merge($defaults, $utm), fn ($value) => $value !== null && $value !== '');

        return $baseUrl.'?'.http_build_query($query);
    }
}

if (! function_exists('conversion_landing_url')) {
    /**
     * Build a conversion landing page URL with UTM parameters.
     *
     * @param  array<string, string>  $utm
     */
    function conversion_landing_url(string $campaign = 'default', array $utm = []): string
    {
        $map = [
            'default' => 'enquire',
            'refinance' => 'enquire_refinance',
            'home-loans' => 'enquire_home_loans',
            'first-home-buyer' => 'enquire_fhb',
            'investment' => 'enquire_investment',
            'commercial' => 'enquire_commercial',
        ];

        return ad_landing_url($map[$campaign] ?? 'enquire', $utm);
    }
}

if (! function_exists('conversion_landing_url_for_slug')) {
    function conversion_landing_url_for_slug(string $slug): string
    {
        if ($slug === 'default') {
            return route('enquire.show');
        }

        return route('enquire.campaign', ['campaign' => $slug]);
    }
}

if (! function_exists('phone_country_codes')) {
    /**
     * @return array<string, string>
     */
    function phone_country_codes(): array
    {
        return [
            '+61' => '+61 AU',
            '+64' => '+64 NZ',
            '+91' => '+91 IN',
            '+44' => '+44 UK',
            '+1' => '+1 US/CA',
            '+65' => '+65 SG',
            '+971' => '+971 UAE',
        ];
    }
}

if (! function_exists('split_lead_phone')) {
    /**
     * @return array{phone_country_code: string, phone: string}
     */
    function split_lead_phone(?string $value = null): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [
                'phone_country_code' => '+61',
                'phone' => '',
            ];
        }

        $normalized = preg_replace('/[\s\-().]/', '', $value) ?? $value;

        if (! str_starts_with($normalized, '+')) {
            return [
                'phone_country_code' => '+61',
                'phone' => $value,
            ];
        }

        $codes = array_keys(phone_country_codes());
        usort($codes, fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($codes as $code) {
            if (str_starts_with($normalized, $code)) {
                $national = substr($normalized, strlen($code));

                if ($code === '+61' && $national !== '' && ! str_starts_with($national, '0')) {
                    $national = '0'.$national;
                }

                return [
                    'phone_country_code' => $code,
                    'phone' => $national,
                ];
            }
        }

        return [
            'phone_country_code' => '+61',
            'phone' => $value,
        ];
    }
}

if (! function_exists('compose_lead_phone')) {
    function compose_lead_phone(?string $countryCode, ?string $national): string
    {
        $countryCode = trim((string) $countryCode) ?: '+61';
        $national = trim((string) $national);
        $digits = preg_replace('/\D+/', '', $national) ?? '';
        $codeDigits = ltrim($countryCode, '+');

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, $codeDigits) && strlen($digits) > strlen($codeDigits) + 5) {
            return '+'.$digits;
        }

        if ($countryCode === '+61' && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '+'.$codeDigits.$digits;
    }
}

if (! function_exists('normalize_validated_lead_phone')) {
    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    function normalize_validated_lead_phone(array $validated): array
    {
        $validated['phone'] = compose_lead_phone(
            isset($validated['phone_country_code']) ? (string) $validated['phone_country_code'] : null,
            isset($validated['phone']) ? (string) $validated['phone'] : null,
        );

        unset($validated['phone_country_code']);

        return $validated;
    }
}

if (! function_exists('lead_email_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_email_rules(): array
    {
        return ['required', 'email', 'max:255', new \App\Rules\ValidLeadEmail];
    }
}

if (! function_exists('lead_name_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_name_rules(): array
    {
        return ['required', 'string', 'max:120', new \App\Rules\ValidLeadName];
    }
}

if (! function_exists('lead_phone_country_code_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_phone_country_code_rules(): array
    {
        return ['required', 'string', 'in:'.implode(',', array_keys(phone_country_codes()))];
    }
}

if (! function_exists('lead_phone_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_phone_rules(): array
    {
        return ['required', 'string', 'max:50', new \App\Rules\ValidLeadPhoneNumber];
    }
}

if (! function_exists('lead_message_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_message_rules(int $max = 2000): array
    {
        return ['required', 'string', 'max:'.$max, new \App\Rules\ValidLeadMessage];
    }
}

if (! function_exists('apply_lead_identity_checks')) {
    function apply_lead_identity_checks(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator): void {
            $data = $validator->getData();
            $first = strtolower(trim((string) ($data['first_name'] ?? '')));
            $last = strtolower(trim((string) ($data['last_name'] ?? '')));

            if ($first !== '' && $last !== '' && $first === $last) {
                $validator->errors()->add('last_name', 'Please enter your real first and last name.');
            }
        });
    }
}
