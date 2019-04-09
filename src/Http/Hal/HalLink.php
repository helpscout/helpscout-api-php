<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

use HelpScout\Api\Exception\RuntimeException;
use QL\UriTemplate\UriTemplate;

class HalLink
{
    /**
     * The first page.
     */
    const REL_FIRST = 'first';

    /**
     * The last page.
     */
    const REL_LAST = 'last';

    /**
     * The next page.
     */
    const REL_NEXT = 'next';

    /**
     * The previous page.
     */
    const REL_PREVIOUS = 'previous';

    /**
     * The self relation.
     */
    const REL_SELF = 'self';

    /**
     * @var string
     */
    private $rel;

    /**
     * @var string
     */
    private $href;

    /**
     * @var bool
     */
    private $templated;

    /**
     * @param string $rel
     * @param string $href
     * @param bool   $templated
     */
    public function __construct(string $rel, string $href, bool $templated)
    {
        $this->rel = $rel;
        $this->href = $href;
        $this->templated = $templated;
    }

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return bool
     */
    public function isTemplated(): bool
    {
        return $this->templated;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function expand(array $params): string
    {
        if (!$this->isTemplated()) {
            throw new RuntimeException(sprintf('The link "%s" is not templated', $this->getRel()));
        }

        return (new UriTemplate($this->getHref()))->expand($params);
    }
}
