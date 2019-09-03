<?php

namespace App\Service;

class Error implements \JsonSerializable
{
    /**
     * @var array
     */
    private $global = [];
    /**
     * @var bool
     */
    private $root;
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @param bool $root
     */
    public function __construct($root = false)
    {
        $this->root = $root;
    }

    /**
     * @param $global
     *
     * @return $this
     */
    public function addGlobal($global)
    {
        $this->global[] = $global;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasFields()
    {
        return count($this->fields) > 0;
    }

    /**
     * @param string $index
     * @param string $field
     *
     * @return $this
     */
    public function pushField($index, $field)
    {
        if (!isset($this->fields[$index])) {
            $this->fields[$index] = [];
        }
        $this->fields[$index][] = $field;

        return $this;
    }

    /**
     * @param $name
     *
     * @return Error
     */
    public function getSubError($name)
    {
        if (!isset($this->fields[$name])) {
            $this->fields[$name] = new self();
        }

        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        if (!$this->root && !$this->hasFields()) {
            return $this->global;
        }

        return [
            'global' => $this->global,
            'fields' => (object) $this->fields,
        ];
    }
}
