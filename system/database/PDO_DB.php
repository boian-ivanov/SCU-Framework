<?php

class PDO_DB extends PDO {

    public function __construct($sql = '') {
        $dsn = DB_DRIVER . ":dbname=" . DB_DATABASE . ";host=" . DB_HOSTNAME;
        parent::__construct($dsn, DB_USERNAME, DB_PASSWORD);
    }
}