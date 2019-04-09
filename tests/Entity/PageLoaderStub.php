<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Entity;

class PageLoaderStub
{
    private $collection;
    private $calls = [];

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function __invoke()
    {
        $this->calls[] = func_get_args();

        return $this->collection;
    }

    public function getCalls(): array
    {
        return $this->calls;
    }
}
