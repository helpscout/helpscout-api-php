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
     * @var boolean
     */
    protected $required;

    /**
     * @var int
     */
    protected $order;

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
            $this->required = isset($data->required) ? (bool) $data->required : false;
            $this->order = isset($data->order) ? $data->order : null;
            $this->options = isset($data->options) ? (array) $data->options : null;
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

    /**
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean
     */
    public function setRequired($value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException('Value must be a boolean');
        }

        $this->required = $value;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int
     */
    public function setOrder($value)
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Value must be an integer');
        }

        $this->order = $value;
    }
}
