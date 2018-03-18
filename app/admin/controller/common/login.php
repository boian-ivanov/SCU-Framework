<?php

class ControllerCommonLogin extends Controller {

    public function index() {
        echo '<pre>' . __FILE__ . ' : ' . __LINE__ . ' -> ' . __METHOD__ . '<br>';
        var_dump($_POST);
        die();
    }
}