<?php

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

if (! function_exists('lead_phone_rules')) {
    /**
     * @return list<\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    function lead_phone_rules(): array
    {
        return ['required', 'string', 'max:50', new \App\Rules\ValidAustralianPhone];
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
