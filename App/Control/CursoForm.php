<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Container\Table;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Form\Label;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\Combo;
use Sheep\Widgets\Form\Button;
use Sheep\Widgets\Form\RadioGroup;
use Sheep\Database\Transaction;
use Sheep\Database\Repository;
use Sheep\Database\Criteria;

use Sheep\Widgets\Wrapper\DatagridWrapper;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;

use Sheep\Traits\SaveTrait;
use Sheep\Traits\EditTrait;

/**
 * Cadastro de Produtos
 */
class CursoForm extends Page
{
    private $form; // formulário
    
    use SaveTrait;
    use EditTrait;
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection = 'trunfo';
        $this->activeRecord = 'Curso';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_curso'));
        
        // cria os campos do formulário
        $codigo      = new Entry('id');
        $nome   = new Entry('nome');
        
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        
        $this->form->addField('Código',    $codigo, 100);
        $this->form->addField('Nome', $nome, 300);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // cria um painél para conter o formulário
        $panel = new Panel('Cursos');
        $panel->add($this->form);
        
        // adiciona o formulário na página
        parent::add($panel);
    }
}
