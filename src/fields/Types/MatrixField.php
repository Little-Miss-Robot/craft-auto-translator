<?php

namespace littlemissrobot\autotranslator\fields\Types;

use craft\elements\Entry;
use littlemissrobot\autotranslator\contracts\FieldInterface;
use littlemissrobot\autotranslator\contracts\FieldResolverInterface;
use littlemissrobot\autotranslator\fields\Field;

class MatrixField extends Field implements FieldInterface
{
    /**
     * @param string $fromLanguage
     * @param string $toLanguage
     * @param Entry $translateEntry
     * @return void
     * @throws \craft\errors\InvalidFieldException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function translate(string $fromLanguage, string $toLanguage, Entry $translateEntry): void
    {
        $fieldResolver = \Craft::$container->get(FieldResolverInterface::class);
        $field = $fieldResolver->getFieldInstance($this->originalEntry, $this->handle);

        if (! $field) {
            return;
        }

        $originalContent = $this->originalEntry->getFieldValue($this->handle);

        dd($field, $originalContent);

        // @TODO translate
    }
}
