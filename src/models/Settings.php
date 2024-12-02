<?php

namespace littlemissrobot\autotranslator\models;

use craft\base\Model;
use craft\fieldlayoutelements\entries\EntryTitleField;
use craft\fields\Matrix;
use craft\fields\PlainText;
use craft\fields\Table;
use littlemissrobot\autotranslator\fields\Types\MatrixField;
use littlemissrobot\autotranslator\fields\Types\RichTextField;
use littlemissrobot\autotranslator\fields\Types\TableField;
use littlemissrobot\autotranslator\fields\Types\TextField;
use littlemissrobot\autotranslator\services\ReverseWordsTranslationService;

/**
 * Class Settings
 * */
class Settings extends Model
{
    /**
     * @var bool $enabled
     */
    public bool $enabled = true;

    /**
     * @var string $fromLanguage
     */
    public string $fromLanguage = '';

    /**
     * @var array $toLanguages
     */
    public array $toLanguages = [];

    /**
     * @var string $service
     */
    public string $service = 'deepl';

    /**
     * @var array $policy
     */
    public array $policy = [
        'class' => \littlemissrobot\autotranslator\policies\DefaultPolicy::class,
    ];

    /**
     * @var array $services
     */
    public array $services = [
        'deepl' => [
            'class' => \littlemissrobot\autotranslator\services\DeeplTranslationService::class,
            'apiKey' => '05727dce-9cdc-4ca3-be6f-260f740fed54:fx',
        ],
        'google' => [
            'class' => \littlemissrobot\autotranslator\services\GoogleCloudTranslationService::class,
            'project' => '$GOOGLE_TRANSLATE_PROJECT',
            'location' => '$GOOGLE_TRANSLATE_LOCATION',
            'options' => [
                'credentials' => 'GOOGLE_TRANSLATE_KEY',
            ],
        ],
        'reverse' => [
            'class' => ReverseWordsTranslationService::class,
        ],
    ];

    /**
     * @var array $translate
     */
    public array $translate = [];

    /**
     * @var array $fields
     */
    public array $fields = [
        PlainText::class => TextField::class,
        Matrix::class => MatrixField::class,
        Table::class => TableField::class,
        EntryTitleField::class => TextField::class,
        'craft\\ckeditor\\Field' => RichTextField::class,
        'craft\\redactor\\Field' => RichTextField::class,
    ];

    /**
     * @return array[]
     */
    protected function defineRules(): array
    {
        return [
            [['enabled', 'fromLanguage', 'toLanguages', 'service', 'services', 'policy', 'fields'], 'required'],
        ];
    }
}
