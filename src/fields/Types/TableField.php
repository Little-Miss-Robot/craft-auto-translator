<?php

namespace littlemissrobot\autotranslator\fields\Types;

use craft\elements\Entry;
use littlemissrobot\autotranslator\contracts\FieldInterface;
use littlemissrobot\autotranslator\contracts\FieldResolverInterface;
use littlemissrobot\autotranslator\fields\Field;

class TableField extends Field implements FieldInterface
{
    /**
     * @var array|string[] $translatableFields
     */
    protected array $translatableFields = ['singleline', 'multiline'];

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

        $translatedContent = [];

        foreach ($field->columns as $column) {

            $type = $column['type'];
            $handle = $column['handle'];

            foreach ($originalContent as $key => $data) {
                if (isset($data[$handle])) {
                    $content = $data[$handle];
                    if (in_array($type, $this->translatableFields)) {
                        $translatedContent[$key][$handle] = $this->service->translate($content, $fromLanguage, $toLanguage);
                    } else {
                        $translatedContent[$key][$handle] = $content;
                    }
                }
            }
        }

        $translateEntry->setFieldValue($this->handle, $translatedContent);
    }
}
