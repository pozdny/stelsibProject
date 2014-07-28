<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 12.01.14
 * Time: 22:13
 */

class Cheek extends Shelves{
    public $shelves;
    public $images_view;
    public $table_view;
    public function __construct($shelves, $row, $cat_id){
        $this->shelves = $shelves;
        $this->images_view = parent::getImagesSub($row);
        $this->table_view = parent::getTableSub($row, $cat_id);
    }
    public function  cost(){
        return .20 + $this->shelves->cost();
    }

} 