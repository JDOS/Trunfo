<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sheep\Widgets\Container;
use Sheep\Widgets\Base\Element;
/**
 * Description of Grid
 *
 * @author Daniel
 */
class Grid extends Element {
    
    private $body;
    
    public function __construct($type='sm', $size='4')
    {
        
        parent::__construct('div');
        
        $this->class='row';
        
        $this->body= new Element('div');
        $this->body->class = 'col-'.$type.'-'.$size;
        parent::add($this->body);
        
        
    }
    public function addRow(){
        $row = new Element('div');
        $row->class = 'row';
    }

    public function  addCol($type='sm', $size='4'){
        $this->body= new Element('div');
        $this->body->class = 'col-'.$type.'-'.$size;
        parent::add($this->body);
    }

    public function add($content){
        
       $this->body->add($content);
    }
    
}
