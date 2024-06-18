<?php

namespace Lmr\AutoTranslator\models;

use craft\base\Model;
use craft\fieldlayoutelements\entries\EntryTitleField;
use craft\fields\Matrix;
use craft\fields\PlainText;
use craft\fields\Table;
use Lmr\AutoTranslator\fields\Types\MatrixField;
use Lmr\AutoTranslator\fields\Types\RichTextField;
use Lmr\AutoTranslator\fields\Types\TableField;
use Lmr\AutoTranslator\fields\Types\TextField;
use Lmr\AutoTranslator\services\ReverseWordsTranslationService;

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
     * @var array $fromLanguages
     */
    public array $fromLanguages = [];

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
        'class' => \Lmr\AutoTranslator\policies\DefaultPolicy::class,
    ];

    /**
     * @var array $services
     */
    public array $services = [
        'deepl' => [
            'class' => \Lmr\AutoTranslator\services\DeeplTranslationService::class,
            'apiKey' => '05727dce-9cdc-4ca3-be6f-260f740fed54:fx',
        ],
        'google' => [
            'class' => \Lmr\AutoTranslator\services\GoogleCloudTranslationService::class,
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
            [['enabled', 'fromLanguages', 'toLanguages', 'service', 'services', 'policy', 'fields'], 'required'],
        ];
    }
}
