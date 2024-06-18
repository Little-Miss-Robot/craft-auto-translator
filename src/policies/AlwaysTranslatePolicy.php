<?php

namespace Lmr\AutoTranslator\policies;

use craft\elements\Entry;
use Lmr\AutoTranslator\contracts\PolicyInterface;

class AlwaysTranslatePolicy implements PolicyInterface
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function shouldTranslate(Entry $entry): bool
    {
        return true;
    }
}
