<?php

namespace Lmr\AutoTranslator\policies;

use craft\elements\Entry;
use Lmr\AutoTranslator\contracts\PolicyInterface;

class NeverTranslatePolicy implements PolicyInterface
{
    /**
     * @param Entry $entry
     * @return bool
     */
    public function shouldTranslate(Entry $entry): bool
    {
        return false;
    }
}
