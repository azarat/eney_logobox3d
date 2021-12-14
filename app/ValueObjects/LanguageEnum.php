<?php

namespace App\ValueObjects;

final class LanguageEnum
{
    const LANGUAGES = [
        'ru',
        'ua',
        'en',
        'fr',
        'es',
        'de'
    ];

    const DEFAULT_LANGUAGE = 'ua';
}