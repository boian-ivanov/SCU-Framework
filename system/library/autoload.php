<?php
/**
 * Author : Boian Ivanov
 * Date   : 5/9/2018
 */

class autoload {
    protected $path = 'node_modules/';

    public function load_modules() {
        $modules = ROOT . $this->path;
        if(is_dir($modules)) {
            echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
            foreach (new DirectoryIterator($modules) as $item) {
                if($item->isDir()) {
                    $module_name = $item->getBasename();
                    foreach(new DirectoryIterator($item->getRealPath()) as  $subpath) {
                        /*var_dump($subpath->getBasename(), $subpath->key());*/
                        
                    }
                }
            }
        }
        die();
    }
}