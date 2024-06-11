<?php

namespace Lmr\AutoTranslator\Translator;

use Craft;
use craft\elements\Entry;
use craft\errors\ElementNotFoundException;
use Lmr\AutoTranslator\Contracts\EntryTranslatorInterface;
use Lmr\AutoTranslator\Contracts\FieldResolverInterface;
use Lmr\AutoTranslator\Fields\Resolver;
use Lmr\AutoTranslator\Plugin;
use yii\base\Exception;

class EntryTranslator implements EntryTranslatorInterface
{
    /**
     * @var Resolver $fieldResolver
     */
    private FieldResolverInterface $fieldResolver;

    /**
     * @param Resolver $fieldResolver
     */
    public function __construct(FieldResolverInterface $fieldResolver)
    {
        $this->fieldResolver = $fieldResolver;
    }

    /**
     * @param Entry $originalEntry
     * @param Entry $translateEntry
     * @param $fromLanguage
     * @param $toLanguage
     * @return void
     * @throws \Throwable
     * @throws ElementNotFoundException
     * @throws Exception
     */
    public function translate(Entry $originalEntry, Entry $translateEntry, $fromLanguage, $toLanguage)
    {
        $config = Plugin::getInstance()->getSettings();

        $section = $translateEntry->section;
        $sectionHandle = $section->handle;
        $entryTypeHandle = $translateEntry->type->handle;

        $identifier = Plugin::getInstance()->fieldService->getIdentifierForSection($sectionHandle, $entryTypeHandle);
        $translateFields = $config->translate[$identifier];

        // Loop through each field and translate its contents
        foreach ($translateFields as $fieldName) {

            $field = $this->fieldResolver->resolve($originalEntry, $fieldName);

            if (!$field) {
                continue;
            }

            // Translate!
            $field->translate($fromLanguage, $toLanguage, $translateEntry);
        }

        // Save the translated entry
        Craft::$app->elements->saveElement($translateEntry);
    }
}
