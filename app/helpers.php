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
