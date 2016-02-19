<?php
namespace HelpScout\model\ref\customfields;

abstract class AbstractCustomFieldRef
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options;

    public function __construct($data = null)
    {
        if ($data) {
            $this->id = isset($data->id) ? $data->id : null;
            $this->name = isset($data->name) ? $data->name : null;
            $this->value = isset($data->value) ? $data->value : null;
            $this->type = isset($data->type) ? $data->type : null;
            $this->options = isset($data->options) ? $data->options : null;
        }
    }

    abstract public function validate($value);

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
