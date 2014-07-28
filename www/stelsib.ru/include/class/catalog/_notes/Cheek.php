<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 12.01.14
 * Time: 22:13
 */

class Cheek extends Shelves{
    public $shelves;
    public $content;
    public function __construct($shelves, $row){
        $this->shelves = $shelves;
        $this->content = parent::getContentSub($row);
    }
    public function  cost(){
        return .20 + $this->shelves->cost();
    }

} 