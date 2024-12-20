<?php

use craft\fields\Matrix;
use craft\fields\PlainText;
use craft\fields\Table;
use craft\fieldlayoutelements\entries\EntryTitleField;
use littlemissrobot\autotranslator\fields\Types\MatrixField;
use littlemissrobot\autotranslator\fields\Types\TextField;
use littlemissrobot\autotranslator\fields\Types\RichTextField;
use littlemissrobot\autotranslator\fields\Types\TableField;

return [
    /**
     * A multidimensional array with as key the section handle name
     * and as value an array of field handles to translate
     *
     * E.g.
     * [
     *   'pages' => ['title', 'description'],
     *   'projects' => ['title', 'description', 'text'],
     * ]
     */
    'translate' => [],

    /**
     * The supported fields and their corresponding classes
     * responsible for their translations
     */
    'fields' => [
        PlainText::class => TextField::class,
        Matrix::class => MatrixField::class,
        Table::class => TableField::class,
        EntryTitleField::class => TextField::class,
        'craft\\ckeditor\\Field' => RichTextField::class,
        'craft\\redactor\\Field' => RichTextField::class,
    ]
];
