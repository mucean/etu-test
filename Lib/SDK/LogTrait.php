<?php

namespace Lib\SDK;

/**
 * Trait LogTrait
 * @author yourname
 */
trait LogTrait
{
    protected function logError($message)
    {
        error_log($message);
    }
}
