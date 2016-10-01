<?php
namespace Sheep\Widgets\Form;

/**
 * classe Entry
 * classe para construção de caixas de texto
 * @author Jonatas D O S
 */
class NumberField extends Field implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;     // nome da TAG
        $this->tag->value = $this->value;   // valor da TAG
        $this->tag->type = 'number';          // tipo de input
		$this->tag->min = 0;
        $this->tag->style = "width:{$this->size}px"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->readonly = "1";
        }
        // exibe a tag
        $this->tag->show();
    }
}