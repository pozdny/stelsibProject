<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 12.01.14
 * Time: 21:39
 */

class Polochniy extends Shelves{
    public $content;
    public function __construct($row){
        $this->content = parent::getContentCat($row);
        parent::$description = "Полочный стеллаж";
    }
    public function cost(){
        return;
    }

} 