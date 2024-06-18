<?php

namespace Lmr\AutoTranslator\contracts;

use craft\elements\Entry;

interface PolicyInterface
{
    public function shouldTranslate(Entry $entry): bool;
}
