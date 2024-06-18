<?php

namespace Lmr\AutoTranslator\contracts;

use craft\elements\Entry;

interface EntryTranslatorInterface
{
    public function translate(Entry $originalEntry, Entry $translateEntry, string $fromLanguage, string $toLanguage);
}
