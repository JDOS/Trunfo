<?php
namespace Sheep\Widgets\Container;

use Sheep\Widgets\Base\Element;

/**
 * Empacota elementos em painel Bootstrap
 * @author Pablo Dall'Oglio
 */
class Card extends Element
{
    private $body;
    
    /**
     * Constrói o painel
     */
    public function __construct($card_title = NULL)
    {
        parent::__construct('div');
        $this->class = 'responsive';
        
        if ($card_title)
        {
            $head = new Element('div');
            $head->class = 'panel-heading';
        
            $label = new Element('h4');
            $label->add($card_title);
     
            $title = new Element('div');
            $title->class = 'panel-title';
            $title->add( $label );
            $head->add($title);
            parent::add($head);
        }
        $this->gallery = new Element('div');
        $this->gallery->class = 'gallery';
        
        $this->body = new Element('div');
        $this->body->class = 'desc';
        $this->gallery->add($this->body);     
        
        $this->buttom = new Element('button');
        $this->buttom->type ='button';
        $this->buttom->class ='btn btn-info btn-sm btn-block';
        $this->buttom->add('Info');
        $this->gallery->add($this->buttom);
       
        parent::add($this->gallery);
    }
    
    /**
     * Adiciona conteúdo
     */
    public function add($content)
    {
        $this->body->add($content);
    }
}
