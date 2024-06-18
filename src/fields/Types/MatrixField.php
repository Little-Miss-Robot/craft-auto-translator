<?php

namespace Lmr\AutoTranslator\fields\Types;

use craft\elements\Entry;
use Lmr\AutoTranslator\contracts\FieldInterface;
use Lmr\AutoTranslator\contracts\FieldResolverInterface;
use Lmr\AutoTranslator\fields\Field;

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

        dd($field);

        // @TODO translate
    }
}
