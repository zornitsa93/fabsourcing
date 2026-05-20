<?php

if (!function_exists('lang_val')) {
    /**
     * Extract the value for the given language from a JSON-encoded multilingual string.
     *
     * Usage in blade:  {{ lang_val($page->title, $lang) }}
     *                  {{ lang_val($page->title) }}          ← uses current app locale
     *
     * Falls back to the first available language if the requested one is missing.
     */
    function lang_val(?string $json, ?string $langCode = null): string
    {
        if (!$json) return '';
        $data = json_decode($json, true);
        if (!is_array($data)) return $json; // not multilingual JSON, return raw value
        $langCode = $langCode ?? app()->getLocale();
        return (string) ($data[$langCode] ?? reset($data) ?? '');
    }
}

if (!function_exists('lang_val_raw')) {
    /**
     * Same as lang_val() but returns null instead of '' when value is missing.
     * Useful when you need to distinguish "not set" from "empty string".
     */
    function lang_val_raw(?string $json, ?string $langCode = null): ?string
    {
        if (!$json) return null;
        $data = json_decode($json, true);
        if (!is_array($data)) return $json;
        $langCode = $langCode ?? app()->getLocale();
        return $data[$langCode] ?? null;
    }
}
