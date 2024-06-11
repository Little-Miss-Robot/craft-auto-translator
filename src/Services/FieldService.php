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

        return [
            'name' => $sectionName . ": " . $group['name'],
            'nativeFields' => $nativeTranslatableFields ?? [],
            'tabs' => $fieldLayout->getTabs(),
            'handle' => $identifier,
        ];

    }
}
