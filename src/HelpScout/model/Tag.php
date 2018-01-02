<?php

namespace Helpscout\model;

class Tag
{
    private $id;
    private $tag;
    private $count;
    private $color;
    private $slug;
    private $createdAt;
    private $modifiedAt;

    public function __construct($data = null)
    {
        $properties = array('id', 'tag', 'count', 'color', 'slug', 'createdAt', 'modifiedAt');
        if ($data) {
            foreach ($properties as $property) {
                $this->$property = isset($data->$property) ? $data->$property : null;
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return the $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return the $color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return the $modifiedAt
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @return the $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
