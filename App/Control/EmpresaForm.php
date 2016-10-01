<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\Combo;
use Sheep\Widgets\Form\CheckGroup;
use Sheep\Database\Transaction;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;

/**
 * Formulário de Empresa
 */
class EmpresaForm extends Page
{
    private $form;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_empresa'));
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $nome_fantasia      = new Entry('nome_fantasia');
        $bairro    = new Entry('bairro');
		$rua       = new Entry('rua');
		$numero    = new Entry('numero');
		$cep    = new Entry('CEP');
        $telefone1  = new Entry('telefone_1');
		$telefone2  = new Entry('telefone_2');
        $email     = new Entry('email');
        $cidade    = new Combo('id_cidade');
        
        
        // carrega as cidades do banco de dados
        Transaction::open('trunfo');
        $cidades = Cidade::all();
        $items = array();
        foreach ($cidades as $obj_cidade) {
            $items[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->addItems($items);
        
        Transaction::close();
        
        $this->form->addField('Código', $codigo, 40);
        $this->form->addField('Nome', $nome_fantasia, 300);
        $this->form->addField('Bairro', $bairro, 300);
        $this->form->addField('Rua', $rua, 200);
		$this->form->addField('Número', $numero, 100);
		$this->form->addField('CEP', $cep, 150);
        $this->form->addField('Telefone 1', $telefone1, 200);
		$this->form->addField('Telefone 2', $telefone2, 200);
        $this->form->addField('Email', $email, 200);
        $this->form->addField('Cidade', $cidade, 200);
        
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $nome_fantasia->setSize(300);
        
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
       // cria um painél para conter o formulário
        $panel = new Panel('Empresas');
        $panel->add($this->form);
        
        // adiciona o formulário na página
        parent::add($panel);
    }

    /**
     * Salva os dados do formulário
     */
    public function onSave()
    {
        try
        {
            // inicia transação com o BD
            Transaction::open('trunfo');

            $dados = $this->form->getData();
            $this->form->setData($dados);
            $empresa = new Empresa; // instancia objeto
            $empresa->fromArray( (array) $dados); // carrega os dados
            $empresa->store(); // armazena o objeto no banco de dados
            
                      
            Transaction::close(); // finaliza a transação
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e)
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());

            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
    
    /**
     * Carrega registro para edição
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                $id = $param['id']; // obtém a chave
                Transaction::open('trunfo'); // inicia transação com o BD
                $empresa = Empresa::find($id);
                $this->form->setData($empresa); // lança os dados da pessoa no formulário
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)		    // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
