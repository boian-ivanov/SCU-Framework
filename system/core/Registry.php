<?php

class Registry {
    private $registry = array();

    public function set($key, $value) {
        $this->registry[$key] = $value;
    }

    public function get($key) {
        return (isset($this->registry[$key]) ? $this->registry[$key] : null);
    }
}