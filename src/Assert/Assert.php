<?php

declare(strict_types=1);

namespace HelpScout\Api\Assert;

use HelpScout\Api\Exception\InvalidArgumentException;
use Webmozart\Assert\Assert as BaseAssert;

class Assert extends BaseAssert
{
    /**
     * {@inheritdoc}
     *
     * @return InvalidArgumentException
     */
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
