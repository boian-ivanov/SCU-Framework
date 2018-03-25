<?php


class Url {

    public $root;

    public function __construct() {
        $this->setDefaults();
    }

    public function setDefaults() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != null ? 'https://' : 'http://';
        $this->root = $protocol . $_SERVER['HTTP_HOST'];
    }
}