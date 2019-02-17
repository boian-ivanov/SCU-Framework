<?php

class ModelCommonIndex extends Model{

    public function index() {
        var_dump($this->db->create([
            'name' => 'x',
            'cols' => [
                'id' => 'INT(11) NOT NULL',
                'qwe' => 'INT(11) NOT NULL',
                'asd' => 'INT(11) NOT NULL',
                'zxc' => 'INT(11) NOT NULL'
            ],
            'engine' => 'myisam',
            'debug' => true
        ]));
//        var_dump($this->db->select('test', ['col1', 'col2', 'col3'])->where(['col1 = 1', 'col2 = 2'])->exec());
//        var_dump($this->db->select('test', ['col1', 'col2', 'col3'])->where(['col1 = 1', 'col2 = 2'])->dump());
        //var_dump($this->db->query('SELECT * FROM test')->exec());
    }

    public function getTestimonials() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."testimonials` WHERE active = 1")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServices() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."services` WHERE active = 1")->fetchAll(PDO::FETCH_ASSOC);
    }
}