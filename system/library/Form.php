<?php

class Form {
    private $data = array();
    private $form_data = '';

    public function loadData($data = array()) {
        if(!empty($data))
            $this->data = $data;
    }

    public function getForm() {
        if(empty($this->data)) return false;

        $this->generateFormFromJson($this->data);

        return $this->form_data;
    }

    private function generateFormFromJson ($input) {
        if(!is_array($input)) // in case it's directly in json format
            $input = json_decode($input);

        $this->listArrayRecursive($input);
    }

    private function listArrayRecursive($someArray) {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($someArray), RecursiveIteratorIterator::SELF_FIRST);
        $this->startFieldset('');
        foreach ($iterator as $k => $v) {
            $indent = str_repeat('&nbsp;', 2 * $iterator->getDepth());
            // Not at end: show key only
            /*if(!$iterator->hasNext())
                $this->closeFieldset();*/

            if ($iterator->hasChildren()) {
                $this->closeFieldset();
                $this->startFieldset($k);
                //echo "$indent$k :<br>";
                // At end: show key, value and path
            } else {
                for ($p = array(), $i = 0, $z = $iterator->getDepth(); $i <= $z; $i++) {
                    $p[] = $iterator->getSubIterator($i)->key();
                }
                $path = '['.implode('][', $p).']';
                $this->textInputRow($k, $path, $v);
//                echo "$indent$k : $v : path -> $path<br>";
            }
        }
    }

    private function textInputRow ($key, $path, $value) {
        $form  = "<div class='form-group row'>";
        $form .=    "<label for='name' class='col-sm-2 col-form-label'>".ucfirst($key)."</label>";
        $form .=    "<div class='col-sm-10'>";
        $form .=        "<input type='text' class='form-control' name='data$path' value='$value'/>";
        $form .=    "</div>";
        $form .= "</div>";
        $this->form_data .= $form;
    }

    private function startFieldset($key) {
        $form  = "<fieldset>";
        $form .=    "<legend>".ucfirst($key)."</legend>";
        $this->form_data .= $form;
    }

    private function closeFieldset() {
        $this->form_data .= "</fieldset>";
    }
}