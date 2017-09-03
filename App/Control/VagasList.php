<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Container\VBox;
use Sheep\Widgets\Datagrid\Datagrid;
use Sheep\Widgets\Datagrid\DatagridColumn;
use Sheep\Widgets\Datagrid\DatagridAction;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Dialog\Question;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Wrapper\DatagridWrapper;
use Sheep\Database\Transaction;
use Sheep\Database\Repository;
use Sheep\Database\Criteria;
use Sheep\Database\Filter;
use Sheep\Widgets\Container\Panel;

/**
 * Listagem de Vaga
 */
class VagasList extends Page
{
    private $form;     // formulário de buscas
    private $datagrid; // listagem
    private $loaded;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário de buscas
        $this->form = new FormWrapper(new Form('form_busca_vagas'));
        $funcao = new Entry('funcao');
        $this->form->addField('Funcao', $funcao, 300);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Novo', new Action(array(new VagasForm, 'onEdit')));
        
        // instancia objeto Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        // instancia as colunas da Datagrid
        $codigo   = new DatagridColumn('id',         'Código', 'right', 50);
        $funcao     = new DatagridColumn('funcao',       'Função',    'left', 200);
        $quantidade   = new DatagridColumn('quantidade','Quantidade', 'left', 140);
		$empresa   = new DatagridColumn('nome_empresa','Empresa', 'left', 140);

        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($funcao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($empresa);

        // instancia duas ações da Datagrid
        $action1 = new DatagridAction(array(new VagasForm, 'onEdit'));
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
        
        $panel = new Panel('Vagas');
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

    /**
     * Carrega a Datagrid com os objetos do banco de dados
     */
    public function onReload()
    {
        Transaction::open('trunfo'); // inicia transação com o BD
        $repository = new Repository('Vaga');

        // cria um critério de seleção de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        // verifica se o usuário preencheu o formulário
        if ($dados->funcao)
        {
            // filtra pelo nome do empresa
            $criteria->add(new Filter('funcao', 'like', "%{$dados->funcao}%"));
        }

        // carrega as empresas que satisfazem o critério
        $vagas = $repository->load($criteria);
        $this->datagrid->clear();
        if ($vagas)
        {
            foreach ($vagas as $vaga)
            {
                // adiciona o objeto na Datagrid
                $this->datagrid->addItem($vaga);
            }
        }

        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
    }

    /**
     * Pergunta sobre a exclusão de registro
     */
    public function onDelete($param)
    {
        $id = $param['id']; // obtém o parâmetro $id
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('id', $id);
        
        new Question('Deseja realmente excluir o registro?', $action1);
    }

    /**
     * Exclui um registro
     */
    public function Delete($param)
    {
        try
        {
            $id = $param['id']; // obtém a chave
            Transaction::open('trunfo'); // inicia transação com o banco 'trunfo'
            $vaga = Vaga::find($id);
            $vaga->delete(); // deleta objeto do banco de dados
            Transaction::close(); // finaliza a transação
            $this->onReload(); // recarrega a datagrid
            new Message('info', "Registro excluído com sucesso");
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
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
