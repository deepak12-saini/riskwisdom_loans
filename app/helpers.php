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
