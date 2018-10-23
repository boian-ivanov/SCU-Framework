<?php

class Model {
    protected $db;
    protected $table;
    protected $fields = array();

    public function __construct($default = 'PDO'){
        $this->db = new PDO_DB('');
    }

    protected function chooseDB($default = 'PDO') {
        if($default == 'PDO') {
            $this->db = new PDO_DB();
        } else if($default == 'MySQLi') {
            $this->db = new Db('');
        }
    }

}