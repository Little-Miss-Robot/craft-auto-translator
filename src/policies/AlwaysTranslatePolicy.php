<?php

namespace littlemissrobot\autotranslator\policies;

use craft\elements\Entry;
use littlemissrobot\autotranslator\contracts\PolicyInterface;

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
