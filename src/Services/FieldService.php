<?php

namespace Lmr\AutoTranslator\Services;

use Craft;
use craft\db\Query;
use craft\elements\Entry;

use yii\base\Component;

use Throwable;

class FieldService extends Component
{
    // Public Methods
    // =========================================================================

    public function getElementInfo(): array
    {
        $elementInfo = [];

        $fieldLayouts = (new Query())
            ->select(['id', 'type'])
            ->from('{{%fieldlayouts}}')
            ->orderBy('type')
            ->all();

        $fields = Craft::$app->getFields();

        foreach ($fieldLayouts as $fieldLayout) {
            try {
                if (!class_exists($fieldLayout['type'])) {
                    continue;
                }

                $elementType = $fieldLayout['type'];
                $fieldLayout = $fields->getLayoutById($fieldLayout['id']);

                if (!$fieldLayout || !$fieldLayout->getCustomFields()) {
                    continue;
                }

                if ($elementType === Entry::class) {
                    $groupName = Craft::t('app', 'Entry Types');

                    if ($items = $this->getEntryTypeInfo($fieldLayout)) {
                        $elementInfo[$groupName][] = $items;
                    }
                }

            } catch (Throwable $e) {
                // When an element is registered, but the plugin disabled, a fatal error will be thrown, so ignore.
            }
        }

        return $elementInfo;
    }

    public function getIdentifierForSection($sectionHandle, $entryTypeHandle): string
    {
        return $sectionHandle . "-" . $entryTypeHandle;
    }

    // Private Methods
    // =========================================================================

    private function getEntryTypeInfo($fieldLayout): array
    {
        $group = (new Query())
            ->select([
                'id',
                'sectionId',
                'handle',
                'name',
            ])
            ->from('{{%entrytypes}}')
            ->where(['fieldLayoutId' => $fieldLayout->id])
            ->one();

        // Get section info
        $currentSection = Craft::$app->sections->getSectionById($group['sectionId']);
        $sectionHandle = $currentSection->handle;
        $sectionName = $currentSection->name;

        $identifier = $this->getIdentifierForSection($sectionHandle, $group["handle"]);

        // Get field layout tabs
        $fieldLayoutTabs = $fieldLayout->getTabs();
        $tabs = [];

        // Loop over every tab to get needed content
        foreach($fieldLayoutTabs as $tab) {
            $tabElements = $tab->elements;
            $tabName = $tab->name;
            $tabFields = [];

            foreach($tabElements as $element) {
                $elementClass = get_class($element);

                // Check type of elements to differentiate in data
                switch($elementClass) {
                    case "craft\\fieldlayoutelements\\CustomField":
                        $elementField = $element->field;
                        $isTranslatable = $this->getTranslatableFromField($elementField);

                        if ($isTranslatable) {
                            $tabFields[] = [
                                // TODO: double check if this label can also be targetted
                                "name" => $elementField->name,
                                "handle" => $elementField->handle
                            ];
                        }

                    break;

                    case "craft\\fieldlayoutelements\\entries\\EntryTitleField":
                        $isTranslatable = $element->translatable;

                        if ($isTranslatable) {
                            $tabFields[] = [
                                // Get label from title field or use native name
                                "name" => $element->label ?? Craft::t("auto-translator", "native-fields.title"),
                                "handle" => "title"
                            ];
                        }

                    break;
                }
            }

            if ($tabFields) {
                $tabs[] = [
                    "name" => $tabName,
                    "fields" => $tabFields
                ];
            }
        }

        return [
            'name' => $sectionName . ": " . $group['name'],
            'nativeFields' => $nativeTranslatableFields ?? [],
            'tabs' => $tabs,
            'handle' => $identifier,
        ];

    }

    private function getTranslatableFromField($field): bool
    {
        $fieldClass = get_class($field);
        $isTranslatable = false;

        switch($fieldClass) {
            case "craft\\fields\\Matrix":
            case "benf\\neo\\Field":
                $propagationMethod = $field->propagationMethod;

                 // TODO: extends this when we want to support different propagation methods
                $isTranslatable = ($propagationMethod == "all");
            default:
                $isTranslatable = $field->getIsTranslatable();
            break;
        }

        return $isTranslatable;
    }
}
