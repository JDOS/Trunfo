<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\Combo;
use Sheep\Widgets\Form\Label;
use Sheep\Widgets\Form\Button;
use Sheep\Widgets\Container\VBox;
use Sheep\Widgets\Datagrid\Datagrid;
use Sheep\Widgets\Datagrid\DatagridColumn;
use Sheep\Widgets\Datagrid\DatagridAction;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Dialog\Question;
use Sheep\Database\Transaction;
use Sheep\Database\Repository;

use Sheep\Traits\DeleteTrait;
use Sheep\Traits\ReloadTrait;
use Sheep\Traits\SaveTrait;
use Sheep\Traits\EditTrait;

use Sheep\Widgets\Wrapper\DatagridWrapper;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;

/**
 * Cadastro de cidades
 */
class CidadesFormList extends Page
{
    private $form;
    private $datagrid;
    private $loaded;

    use EditTrait;
    use DeleteTrait;
    use ReloadTrait {
        onReload as onReloadTrait;
    }
    use SaveTrait {
        onSave as onSaveTrait;
    }
    
    
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection   = 'trunfo';
        $this->activeRecord = 'Cidade';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_cidades'));
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $descricao = new Entry('nome');
        $estado    = new Combo('id_estado');
        
        $codigo->setEditable(FALSE);
        
        Transaction::open('trunfo');
        $estados = Estado::all();
        $items = array();
        foreach ($estados as $obj_estado)
        {
            $items[$obj_estado->id] = $obj_estado->nome;
        }
        Transaction::close();
        
        $estado->addItems($items);
        
        $this->form->addField('Código', $codigo, 40);
        $this->form->addField('Descrição', $descricao, 300);
        $this->form->addField('Estado', $estado, 300);
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onEdit')));
        
        // instancia a Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        // instancia as colunas da Datagrid
        $codigo   = new DatagridColumn('id',     'Código', 'right', 50);
        $nome     = new DatagridColumn('nome',   'Nome',   'left', 150);
        $estado   = new DatagridColumn('nome_estado', 'Estado', 'left', 150);

        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($estado);

        // instancia duas ações da Datagrid
        $action1 = new DatagridAction(array($this, 'onEdit'));
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
        
        $panel = new Panel('Cidades');
        $panel->add($this->form);
        
        $panel2 = new Panel();
        $panel2->add($this->datagrid);
        
        // monta a página através de uma tabela
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($panel);
        $box->add($panel2);
        
        parent::add($box);
    }
    
    /**
     * Salva os dados
     */
    public function onSave()
    {
        $this->onSaveTrait();
        $this->onReload();
    }
    
    /**
     * Carrega os dados
     */
    public function onReload()
    {
        $this->onReloadTrait();   
        $this->loaded = true;
    }

    /**
     * exibe a página
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
