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
        $routeName = config('riskwisdom.ad_landing_pages.'.$page);

        if (! is_string($routeName) || $routeName === '') {
            return route('home');
        }

        $defaults = [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => $page,
        ];

        $query = array_filter(array_merge($defaults, $utm), fn ($value) => $value !== null && $value !== '');

        return route($routeName).'?'.http_build_query($query);
    }
}
