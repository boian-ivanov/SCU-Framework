<?php

class ModelCommonIndex extends Model{

    public function index() {
        echo "Fuckin model";
        var_dump($this->db->select(['col1', 'col2', 'col3'], 'test')->where('')->exec());
    }
}