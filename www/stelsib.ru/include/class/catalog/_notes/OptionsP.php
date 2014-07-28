<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 18.02.14
 * Time: 1:37
 */

class OptionsP extends Shelves{
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