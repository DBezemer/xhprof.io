<?php

namespace ay\xhprof;

use ArrayAccess;

class LazyConfig implements ArrayAccess
{
    protected $config = array();

    public function __construct($config)
    {
        $this->config += $config;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->config);
    }

    public function offsetGet($offset)
    {
        $value =& $this->config[$offset];

        if (is_callable($value)) {
            $value = call_user_func($value);
        }

        return $value;
    }

    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    public function append($config)
    {
        $newLazy = clone $this;
        $newLazy->config += $config;

        return $newLazy;
    }
}
