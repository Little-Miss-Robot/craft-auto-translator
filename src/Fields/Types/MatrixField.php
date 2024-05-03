<?php

namespace Lmr\AutoTranslator\Fields\Types;

use craft\elements\Entry;
use Lmr\AutoTranslator\Contracts\FieldInterface;
use Lmr\AutoTranslator\Contracts\FieldResolverInterface;
use Lmr\AutoTranslator\Fields\Field;

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
