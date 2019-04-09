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

    /**
     * @param string $rel
     *
     * @return HalLink
     */
    public function get(string $rel): HalLink
    {
        if (!$this->has($rel)) {
            throw new InvalidArgumentException(sprintf('The link "%s" was not found', $rel));
        }

        return $this->links[$rel];
    }

    /**
     * @param string $rel
     *
     * @return string
     */
    public function getHref(string $rel): string
    {
        return $this->get($rel)->getHref();
    }

    /**
     * @param string $rel
     * @param array  $params
     *
     * @return string
     */
    public function expand(string $rel, array $params): string
    {
        return $this->get($rel)->expand($params);
    }

    /**
     * @param string $rel
     *
     * @return bool
     */
    public function has(string $rel): bool
    {
        return array_key_exists($rel, $this->links);
    }

    /**
     * @param HalLink $link
     */
    public function add(HalLink $link)
    {
        $this->links[$link->getRel()] = $link;
    }
}
