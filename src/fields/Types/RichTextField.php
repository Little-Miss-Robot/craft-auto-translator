<?php

namespace littlemissrobot\autotranslator\fields\Types;

use craft\elements\Entry;
use littlemissrobot\autotranslator\contracts\FieldInterface;
use littlemissrobot\autotranslator\fields\Field;

class RichTextField extends Field implements FieldInterface
{
    /**
     * @param string $fromLanguage
     * @param string $toLanguage
     * @param Entry $translateEntry
     * @return void
     * @throws \craft\errors\InvalidFieldException
     */
    public function translate(string $fromLanguage, string $toLanguage, Entry $translateEntry): void
    {
        // Get the original content for this field and entry
        $field = $this->originalEntry->{$this->handle};
        $content = strip_tags($field->getRawContent());

        // Translate it
        $translatedContent = $this->service->translate($content, $fromLanguage, $toLanguage);

        // Save!
        $translateEntry->{$this->handle} = $translatedContent;
    }
}
