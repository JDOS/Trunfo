<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Daniel
 */

use Sheep\Widgets\Container\Panel;
use Sheep\Widgets\Container\Card;
use Sheep\Database\Transaction;
use Sheep\Widgets\Dialog\Message;
use Sheep\Database\Repository;
use Sheep\Database\Criteria;
use Sheep\Database\Filter;
use Sheep\Widgets\Container\Grafico;
use Sheep\Control\Page;
use Sheep\Widgets\Form\Combo;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Entry;
use Sheep\Traits\ReloadTrait;



class View extends Page{
    private $form;
    private $replaces;
    private $filter;
    private $loaded;
    private $cards;
   

    
public function __construct(){
    try {
           
            Transaction::open('trunfo');

            $cursos = Curso::all();
            $cidades = Cidade::all();

            if ($cursos){
                foreach($cursos as $curso){
                    $cursokeyname[$curso->id] = $curso->nome; 
                }
            }
            $comboboxcurso = new Combo('boxcurso');
            $comboboxcurso->addItems($cursokeyname);
            
            if ($cidades){
                foreach($cidades as $cidade){
                    $cidade_array=$curso->toArray();
                    $cidadekeyname[$cidade->id]=$cidade->nome;
                }
            }
            
            $comboboxcidade = new Combo('boxcidade');
            $comboboxcidade->addItems($cidadekeyname);
           
            $pesquisar = new Entry('pesquisar');
            

            $comboboxturno = new Combo('boxturno');
            $comboboxturno->addItems(array( 'Matutino'=>'Matutino',
                                            'Vespertino'=>'Vespertino',
                                            'Noturno'=>'Noturno',
                                            'Integral'=>'Integral',
            ));
        
            $this->form = new FormWrapper(new Form('pesquisa'));
            
            $this->form->addField('Pesquisar', $pesquisar, 300);
            $this->form->addAction('PESQUISAR', new Action(array($this, 'pesquisar')));
            $this->form->addField("Turno",$comboboxturno,200);
            $this->form->addField("Curso",$comboboxcurso,200);
            $this->form->addField("Cidade",$comboboxcidade,200);
            
            
            
            parent::add($this->form);
            
        }

        catch (Exception $e){
                new Message('error', $e->getMessage());
        }

         

    }

    public function pesquisar (){
        
        Transaction::open('trunfo'); // inicia transação com o BD
        $repository = new Repository('Vaga');

        // cria um critério de seleção de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        // verifica se o usuário preencheu o formulário
        if ($dados->boxturno)
        {
            // filtra pelo nome do empresa
            $criteria->add(new Filter('turno_expediente', 'like', "%{$dados->boxturno}%"));
        }

        // carrega as empresas que satisfazem o critério
        $vagas = $repository->load($criteria);
 
        if ($vagas)
        {
                    foreach ($vagas as $vaga){
                            $card = new Card($vaga->funcao);
                            $card->add($vaga->descricao);
                            parent::add($card);
                            
                    }
        }
        
        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
        
         $dados = $this->form->getData();
        
    }

    public function show (){
           
            if (!$this->loaded)
         {
	        $this->pesquisar();
         }

            parent::show();
        }
    }
