<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\Label;
use Sheep\Widgets\Form\Button;
use Sheep\Widgets\Container\Table;
use Sheep\Widgets\Container\VBox;
use Sheep\Widgets\Datagrid\Datagrid;
use Sheep\Widgets\Datagrid\DatagridColumn;
use Sheep\Widgets\Datagrid\DatagridAction;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Dialog\Question;
use Sheep\Database\Transaction;
use Sheep\Database\Repository;
use Sheep\Database\Criteria;
use Sheep\Database\Filter;

use Sheep\Traits\DeleteTrait;
use Sheep\Traits\ReloadTrait;

use Sheep\Widgets\Wrapper\DatagridWrapper;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;

/**
 * Página de Cursos
 */
class CursoList extends Page
{
    private $form;
    private $datagrid;
    private $loaded;
    private $activeRecord;
    private $filter;
    
    use DeleteTrait;
    use ReloadTrait {
        onReload as onReloadTrait;
    }
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        
        // Define o Active Record
        $this->activeRecord = 'Curso';
        $this->connection   = 'trunfo';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_busca_cursos'));
        
        // cria os campos do formulário
        $descricao = new Entry('descricao');
        
        $this->form->addField('Descrição',   $descricao, 300);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Cadastrar', new Action(array(new CursoForm, 'onEdit')));
        
       // instancia objeto Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);
        
        // instancia as colunas da Datagrid
        $codigo   = new DatagridColumn('id',             'Código',    'right',  50);
        $descricao= new DatagridColumn('nome',      'Nome', 'left',   270);
    
        
        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        
        // instancia duas ações da Datagrid
        $obj = new CursoForm;
        $action1 = new DatagridAction(array($obj, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        
        $action2 = new DatagridAction(array($this, 'onDelete'));
        $action2->setLabel('Deletar');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');
        
        // adiciona as ações à Datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // cria o modelo da Datagrid, montando sua estrutura
        $this->datagrid->createModel();
        
        $panel = new Panel('Cursos');
        $panel->add($this->form);
        
        $panel2 = new Panel();
        $panel2->add($this->datagrid);
        
        // monta a página através de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($panel);
        $box->add($panel2);
        
        parent::add($box);
    }
    
    public function onReload()
    {
        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();
        
        // verifica se o usuário preencheu o formulário
        if ($dados->descricao)
        {
            // filtra pela descrição do produto
            $this->filter = new Filter('nome', 'like', "%{$dados->descricao}%");
        }
        
        $this->onReloadTrait();   
        $this->loaded = true;
    }
    
    /**
     * Exibe a página
     */
    public function show()
    {
         // se a listagem ainda não foi carregada
         if (!$this->loaded)
         {
	        $this->onReload();
         }
         parent::show();
    }
}
