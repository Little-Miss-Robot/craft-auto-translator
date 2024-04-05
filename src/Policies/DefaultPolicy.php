<?php

namespace Lmr\AutoTranslator\Policies;

use craft\elements\Entry;
use craft\helpers\ElementHelper;
use Lmr\AutoTranslator\Contracts\Policy;

class DefaultPolicy implements Policy
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function shouldTranslate(Entry $entry): bool
    {
        return ! (ElementHelper::isDraftOrRevision($entry) || $entry->resaving);
    }
}
