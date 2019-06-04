<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

use HelpScout\Api\Exception\InvalidArgumentException;

class HalLinks
{
    /**
     * @var HalLink[]
     */
    private $links = [];

    /**
     * @param HalLink[] $links
     */
    public function __construct(array $links = [])
    {
        foreach ($links as $link) {
            $this->add($link);
        }
    }

    public function get(string $rel): HalLink
    {
        if (!$this->has($rel)) {
            throw new InvalidArgumentException(sprintf('The link "%s" was not found', $rel));
        }

        return $this->links[$rel];
    }

    public function getHref(string $rel): string
    {
        return $this->get($rel)->getHref();
    }

    public function expand(string $rel, array $params): string
    {
        return $this->get($rel)->expand($params);
    }

    public function has(string $rel): bool
    {
        return array_key_exists($rel, $this->links);
    }

    public function add(HalLink $link)
    {
        $this->links[$link->getRel()] = $link;
    }

    public function size(): int
    {
        return count($this->links);
    }
}
