<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal;

use HelpScout\Api\Exception\JsonException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use HelpScout\Api\Http\Hal\HalDocument;
use HelpScout\Api\Http\Hal\VndError;
use HelpScout\Api\Tests\Http\Hal\Entity\StubPayloads;
use HelpScout\Api\Tests\Payloads\ErrorPayloads;
use PHPUnit\Framework\TestCase;

class HalDeserializerTest extends TestCase
{
    public function testDeserializeDocumentWithEntity()
    {
        $halDocument = HalDeserializer::deserializeDocument(StubPayloads::getResource());

        $this->assertInstanceOf(HalDocument::class, $halDocument);
        $this->assertSame(['id' => 1], $halDocument->getData());
    }

    public function testDeserializeDocumentWithCollection()
    {
        $halDocument = HalDeserializer::deserializeDocument(StubPayloads::getResources(5));

        $this->assertInstanceOf(HalDocument::class, $halDocument);
        $this->assertCount(5, $halDocument->getEmbedded('entities'));
    }

    public function testDeserializeDocumentWithCollectionWithEmbeddedEntity()
    {
        $halDocument = HalDeserializer::deserializeDocument(StubPayloads::getResourceWithEmbeddedEntity());

        $this->assertInstanceOf(HalDocument::class, $halDocument);
        $this->assertTrue($halDocument->hasEmbedded('address'));
    }

    public function testDeserializeDocumentWithEmptyCollection()
    {
        $halDocument = HalDeserializer::deserializeDocument(StubPayloads::getResources(0));

        $this->assertInstanceOf(HalDocument::class, $halDocument);
        $this->assertFalse($halDocument->hasEmbedded('entities'));
    }

    public function testDeserializeDocumentThrowsExceptionWithMalformedJson()
    {
        $this->expectException(JsonException::class);

        // The JSON error should be part of the exception message
        $this->expectExceptionMessage('Syntax error');

        HalDeserializer::deserializeDocument('!!');
    }

    public function testDeserializeErrorWithValidationErrors()
    {
        $halDocument = HalDeserializer::deserializeDocument(ErrorPayloads::validationErrors());
        $error = HalDeserializer::deserializeError($halDocument);

        $this->assertInstanceOf(VndError::class, $error);
        $this->assertSame('Validation error', $error->getMessage());
        $this->assertSame('cb73aad3-9a81-44fd-bf70-48250ea256ff#12', $error->getLogRef());
        $this->assertNull($error->getPath());

        $errors = $error->getErrors();
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(VndError::class, $errors[0]);
        $this->assertSame('may not be empty', $errors[0]->getMessage());
        $this->assertNull($errors[0]->getLogRef());
        $this->assertSame('country', $errors[0]->getPath());
    }

    public function testDeserializeErrorWithInternalServerError()
    {
        $halDocument = HalDeserializer::deserializeDocument(ErrorPayloads::internalServerError());
        $error = HalDeserializer::deserializeError($halDocument);

        $this->assertInstanceOf(VndError::class, $error);
        $this->assertSame('Internal error', $error->getMessage());
        $this->assertSame('cb73aad3-9a81-44fd-bf70-48250ea256ff#12', $error->getLogRef());
        $this->assertNull($error->getPath());
        $this->assertCount(0, $error->getErrors());
    }
}
