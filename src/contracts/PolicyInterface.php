<?php

namespace littlemissrobot\autotranslator\contracts;

use craft\elements\Entry;

interface PolicyInterface
{
    public function shouldTranslate(Entry $entry): bool;
}
