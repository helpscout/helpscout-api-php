<?php

declare(strict_types=1);

namespace HelpScout\Api\Exception;

class InvalidSignatureException extends RuntimeException
{
    public function __construct(string $expectedSignature, string $actualSignature = '')
    {
        $actual = $actualSignature === ''
            ? 'No signature'
            : $actualSignature;

        $message = sprintf(
            'Signature mismatch: Expected signature is %s. %s was provided.',
            $expectedSignature,
            $actual
        );

        parent::__construct($message, 0, null);
    }
}
