<?php

namespace littlemissrobot\autotranslator\contracts;

use craft\elements\Entry;

interface FieldInterface
{
    /**
     * @param string $handle
     * @param Entry $originalEntry
     * @param TranslationServiceInterface $service
     */
    public function __construct(string $handle, Entry $originalEntry, TranslationServiceInterface $service);

    /**
     * @param string $fromLanguage
     * @param string $toLanguage
     * @param Entry $translateEntry
     * @return void
     */
    public function translate(string $fromLanguage, string $toLanguage, Entry $translateEntry): void;
}
