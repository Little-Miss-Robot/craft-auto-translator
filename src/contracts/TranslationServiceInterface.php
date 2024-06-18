<?php

namespace Lmr\AutoTranslator\contracts;

interface TranslationServiceInterface
{
    public function translate(string $input, string $fromLanguage, string $toLanguage): string;
}
