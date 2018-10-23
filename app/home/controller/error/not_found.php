<?php

class ControllerErrorNotfound extends Controller{

    public function index() {
        header("HTTP/1.0 404 Not Found");
        echo "The page has not been found. Please try again.";
    }
}