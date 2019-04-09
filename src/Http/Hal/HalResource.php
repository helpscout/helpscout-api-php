<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

class HalResource
{
    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var HalLinks
     */
    private $links;

    /**
     * @param mixed    $entity
     * @param HalLinks $links
     */
    public function __construct($entity, HalLinks $links)
    {
        $this->entity = $entity;
        $this->links = $links;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return HalLinks
     */
    public function getLinks(): HalLinks
    {
        return $this->links;
    }
}
