<?php

class Form {
    private $data = array();
    private $form_data = array();

    public function loadData($data = array()) {
        if(!empty($data))
            $this->data = $data;
    }

    public function getForm() {
        if(empty($this->data)) return false;

        $this->generateFormFromData();

        return implode('',$this->form_data);
    }

    private function generateFormFromData() {
//        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        /*foreach ($this->data as $key => $value) {
            if(!is_array($value)) {
                $this->textInputRow($key, $value);
            } else {
                echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
                var_dump($value);
                die();
            }
        }*/
        /*array_walk($this->data, function ($value, $key) {
            if(is_array($value)) {
                echo '->';
                foreach ($value as $k => $v) {
                    var_dump($k, $v);
                    //echo $k . " => " . $v . PHP_EOL;
                }
            } else {
                echo $key . " => " . $value . PHP_EOL;
            }
        });
        var_dump($this->data);
        die();*/
    }

    private function generateFormFromJson ($input) {
        $input = json_decode($input); // in case it's directly in json format


    }

    private function textInputRow ($key, $value) {
        $form  = "<div class='form-group row'>";
        $form .=    "<label for='name' class='col-sm-2 col-form-label'>$key</label>";
        $form .=    "<div class='col-sm-10'>";
        $form .=        "<input type='text' class='form-control' name='$key' value='$value'/>";
        $form .=    "</div>";
        $form .= "</div>";
        $this->form_data[] = $form;
    }
}