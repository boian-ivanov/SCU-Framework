<?php

class Head {
    private $scripts;
    private $links;

    public function __construct() {
        $this->scripts = [];
        $this->links = [];
    }

    public function addScript($script) {
        if($script)
            $this->scripts[] = '<script src="' . $script . '" type="text/javascript"></script>';
    }

    public function getScripts($key = '') {
        if($key === '')
            return $this->scripts;
        else
            return $this->scripts[$key];
    }

    public function addLinks($data) {
        $link = '<link';

        if(!empty($data['rel']))  $link .= ' rel="'  . $data['rel']  . '"';
        if(!empty($data['href'])) $link .= ' href="' . $data['href'] . '"';
        if(!empty($data['type'])) $link .= ' type="' . $data['type'] . '"';

        $link .= ' />';

        $this->links[] = $link;
    }

    public function getLinks($key = '') {
        if($key === '')
            return $this->links;
        else
            return $this->links[$key];
    }
}