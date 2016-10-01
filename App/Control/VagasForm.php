<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Dialog\Message;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\TimeField;
use Sheep\Widgets\Form\Combo;
use Sheep\Widgets\Form\Text;
use Sheep\Widgets\Form\DateField;
use Sheep\Widgets\Form\NumberField;
use Sheep\Widgets\Form\CheckGroup;
use Sheep\Database\Transaction;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;
/**
 * Formulário de pessoas
 */
class VagasForm extends Page
{
    private $form;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_vagas'));
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $funcao      = new Entry('funcao');
        $quantidade  = new NumberField('quantidade');
        $requisitos    = new Entry('requisitos');
        $descricao  = new Text('descricao');
		$horario_entrada  = new TimeField('horario_entrada');
		$horario_saida  = new TimeField('horario_saida');
        $turno_expediente    = new Combo('turno_expediente');
		$turno_expediente->addItems(array(  'Matutino'=>'Matutino',
											'Vespertino'=>'Vespertino',
											'Noturno'=>'Noturno',
											'Integral'=>'Integral',
											));
		
        $horas_trabalho    = new TimeField('horas_trabalho');
		$empresa  = new Combo('id_empresa');
		$dt_validade  = new DateField('dt_validade');
		$curso    = new CheckGroup('ids_cursos');
        $curso->setLayout('horizontal');
        
        // carrega as empresas do banco de dados
        Transaction::open('trunfo');
        $empresas = Empresa::all();
        $items = array();
        foreach ($empresas as $obj_empresa) {
            $items[$obj_empresa->id] = $obj_empresa->nome_fantasia;
        }
        $empresa->addItems($items);
        
        $cursos = Curso::all();
        $items = array();
        foreach ($cursos as $obj_curso) {
            $items[$obj_curso->id] = $obj_curso->nome;
        }
        $curso->addItems($items);
        Transaction::close();
        
        $this->form->addField('Código', $codigo, 40);
        $this->form->addField('Função', $funcao, 300);
        $this->form->addField('Quantidade', $quantidade, 300);
        $this->form->addField('Requisitos', $requisitos, 200);
        $this->form->addField('Descrição', $descricao, 200);
		$this->form->addField('Horario entrada', $horario_entrada, 200);
		$this->form->addField('Horario saida', $horario_saida, 200);
		$this->form->addField('Periodo', $turno_expediente, 200);
        $this->form->addField('Horas Trabalho', $horas_trabalho, 200);
		$this->form->addField('Data Validade', $dt_validade, 200);
        $this->form->addField('Empresa', $empresa, 400);
        $this->form->addField('Curso', $curso, 200);
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $funcao->setSize(300);
        $requisitos->setSize(300);
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
       // cria um painél para conter o formulário
        $panel = new Panel('Vagas');
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
            $vaga = new Vaga; // instancia objeto
            $vaga->fromArray( (array) $dados); // carrega os dados
            $vaga->store(); // armazena o objeto no banco de dados
				
            $vaga->delCursos();
            if ($dados->ids_cursos) {
                foreach ($dados->ids_cursos as $id_curso)
                {
                    $vaga->addCurso( new Curso($id_curso) );
                }
            }
            
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
                $vaga = Vaga::find($id);
                $vaga->ids_cursos = $vaga->getIdsCurso();
                $this->form->setData($vaga); // lança os dados da pessoa no formulário
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
