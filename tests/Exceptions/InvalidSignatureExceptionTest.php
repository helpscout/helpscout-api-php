<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Exceptions;

use HelpScout\Api\Exception\InvalidSignatureException;
use PHPUnit\Framework\TestCase;

class InvalidSignatureExceptionTest extends TestCase
{
    public function testExceptionMessageWithActualSignature()
    {
        $expectedSignature = '123asdf';
        $actualSignature = 'fdasdfas';

        $message = "Signature mismatch: Expected signature is {$expectedSignature}. {$actualSignature} was provided.";
        $exception = new InvalidSignatureException($expectedSignature, $actualSignature);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionMessageWithoutActualSignature()
    {
        $expectedSignature = '123asdf';

        $message = "Signature mismatch: Expected signature is {$expectedSignature}. No signature was provided.";
        $exception = new InvalidSignatureException($expectedSignature);

        $this->assertSame($message, $exception->getMessage());
    }
}
