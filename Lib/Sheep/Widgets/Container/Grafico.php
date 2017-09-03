<?php

namespace Sheep\Widgets\Container;

use Sheep\Control\Page;
use Sheep\Widgets\Base\Element;

class Grafico extends Element{
    
    private $data;
    
    public function __construct(array $info) {
       
        parent::__construct('script');
        
        $this->charset = 'UTF-8';
        $this->type = 'text/javascript';
              
        
        
        $tam = count($info);
        $i =0;
        $graf ='';
        foreach ($info as $key => $num){
            
            
          $graf .=  "{"."\n".
                   " value: {$num},"."\n".
                   "color: \"#F7464A\","."\n".
                    "highlight: \"#FF5A5E\","."\n".
                    "label: \" {$key} \" "."\n".
                "}";
          
               $i++;
            
             if($i != $tam){
                 $graf .= ','."\n";
             }
             
        }
        
        $content = 'var options = {'."\n".
                                'responsive: true'."\n".
                                 '};'."\n".
                        'var data = ['."\n".

                             $graf 

                       .']'."\n".

                       ' window.onload = function () {'."\n".

                            'var ctx = document.getElementById("GraficoDonut").getContext("2d");'."\n".
                            'var PizzaChart = new Chart(ctx).Doughnut(data, options);'."\n".
                
                          '}'."\n";
            
        $this->add($content);
    
      
    }
    
    //public function __set($propriedade, $valor){
    //    $this->data[$propriedade]=$valor;
   // }
    
    
}