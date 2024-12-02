<?php

namespace littlemissrobot\autotranslator\policies;

use craft\elements\Entry;
use craft\helpers\ElementHelper;
use littlemissrobot\autotranslator\contracts\PolicyInterface;
use littlemissrobot\autotranslator\Plugin;

class DefaultPolicy implements PolicyInterface
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function shouldTranslate(Entry $entry): bool
    {
        $config = Plugin::getInstance()->getSettings();
        $fromLanguage = $config->fromLanguage;

        // The language we've just edited and need to translate from
        $currentLanguage = $entry->site->language;

        // Only translate when the current language is in the list of "from languages"
        if (! ($currentLanguage == $fromLanguage)) {
            return false;
        }

        // Only translate when the entry is enabled for the site
        if (! $entry->enabledForSite) {
            return false;
        }

        return ! (ElementHelper::isDraftOrRevision($entry) || $entry->resaving);
    }
}
