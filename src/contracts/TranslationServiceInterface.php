<?php

namespace littlemissrobot\autotranslator\contracts;

interface TranslationServiceInterface
{
    public function translate(string $input, string $fromLanguage, string $toLanguage): string;
}
