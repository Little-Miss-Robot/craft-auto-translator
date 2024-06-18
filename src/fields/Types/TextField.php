<?php

namespace littlemissrobot\autotranslator\fields\Types;

use craft\elements\Entry;
use littlemissrobot\autotranslator\contracts\FieldInterface;
use littlemissrobot\autotranslator\fields\Field;

class TextField extends Field implements FieldInterface
{
    /**
     * @param string $fromLanguage
     * @param string $toLanguage
     * @param Entry $translateEntry
     * @return void
     */
    public function translate(string $fromLanguage, string $toLanguage, Entry $translateEntry): void
    {
        // Get the original content for this field and entry
        $content = $this->originalEntry->{$this->handle};

        // Translate it
        $translatedContent = $this->service->translate($content, $fromLanguage, $toLanguage);

        // Save!
        $translateEntry->{$this->handle} = $translatedContent;
    }
}
