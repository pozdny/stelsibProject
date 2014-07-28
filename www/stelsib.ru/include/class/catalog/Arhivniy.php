<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 12.01.14
 * Time: 21:32
 */

class Arhivniy extends Shelves{
    public $content;
    public function __construct($row){
        $this->content = parent::getContentCat($row);
        parent::$description = "Архивный стеллаж";
    }
    public function cost(){
            return;
    }

} 