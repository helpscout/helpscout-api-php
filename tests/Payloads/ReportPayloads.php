<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ReportPayloads
{
    public static function getReportData(string $name): string
    {
        $file = __DIR__.'/Reports/'.self::getFileName($name).'.json';

        if (!\file_exists($file)) {
            throw new \InvalidArgumentException('Invalid fixture file - '.$file);
        }

        return \file_get_contents($file);
    }

    private static function getFileName(string $name): string
    {
        $prefix = '/(get)(Company|Conversations|Docs|Happiness|Productivity|User)(\w+)(Report)/';
        $file = \preg_replace_callback($prefix, function ($matches) {
            return strtolower($matches[2]).$matches[3];
        }, $name);

        return $file;
    }
}
