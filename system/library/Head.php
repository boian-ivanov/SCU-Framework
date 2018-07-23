<?php

class Head {
    private $scripts;
    private $links;

    public function __construct() {
        $this->scripts = [];
        $this->links = [];
    }

    public function addScript($data) {
        $script = '<script';

        if (!is_array($data)) {
            $data = [
                'src' => $data,
                'type' => 'text/javascript'
            ];
        }

        foreach ($data as $key => $d) {
            $script .= ' '.$key.'="' . $d . '"';
        }

        $script .= '></script>';

        $this->scripts[] = $script;
    }

    public function addScripts($data = array()) {
        foreach($data as $d) {
            $this->addScript($d);
        }
        return $this->getScripts();
    }

    public function getScripts($key = '') {
        if($key === '')
            return $this->scripts;
        else
            return $this->scripts[$key];
    }

    public function addLink($data) {
        $link = '<link';

        if (!is_array($data)) {
            $data = [
                'href' => $data,
                'rel' => 'stylesheet'
            ];
        }

        foreach ($data as $key => $d) {
            $link .= ' '.$key.'="' . $d . '"';
        }

        $link .= '/>';

        $this->links[] = $link;
    }

    public function addLinks($data = array()) {
        foreach($data as $d) {
            $this->addLink($d);
        }
        return $this->getLinks();
    }

    public function getLinks($key = '') {
        if($key === '')
            return $this->links;
        else
            return $this->links[$key];
    }
}