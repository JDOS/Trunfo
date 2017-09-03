<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrincipalCard
 *
 * @author Daniel
 */

use Sheep\Control\Page;
use Sheep\Widgets\Container\Panel;
use Sheep\Database\Transaction;
use Sheep\Widgets\Dialog\Message;
use Sheep\Database\Repository;
use Sheep\Database\LoggerTXT;
use Sheep\Database\Criteria;
use Sheep\Database\Filter;
use Sheep\Widgets\Container\Card;
use Sheep\Widgets\Container\Grid;
use Sheep\Widgets\Container\Grafico;

class PrincipalCard {
    
    
    public function __construct(){
					
    try {
            $replaces = array();
            Transaction::open('trunfo');

            $vagas = Vaga::all();
            $cursos = Curso::all();
            $cidades = Cidade::all();

            if ($cursos){
                foreach($cursos as $curso){
                    $curso_array=$curso->toArray();
                    $curso_array['id']=$curso->id;
                    $curso_array['nome']=$curso->nome;  
                    $replaces['cursos'][]=$curso_array;
                }
            }

            if ($cidades){
                foreach($cidades as $cidade){
                    $cidade_array=$curso->toArray();
                    $cidade_array['id']=$cidade->id;
                    $cidade_array['nome']=$cidade->nome;  
                    $replaces['cidades'][]=$cidade_array;
                }
            }


            if ($vagas){
                    foreach ($vagas as $vaga){
                            $vaga_array=$vaga->toArray();
                            $vaga_array['id']=$vaga->id;
                            $vaga_array['funcao']=$vaga->funcao;
                            $vaga_array['quantidade']=$vaga->quantidade;
                            $vaga_array['email']=$vaga->email;
                            $vaga_array['telefone']=$vaga->telefone;
                            $vaga_array['descricao']=$vaga->descricao;
                            $vaga_array['periodo']=$vaga->turno_expediente;
                            $vaga_array['criada']=$vaga->dt_criacao;
                            $vaga_array['requisito']=$vaga->requisitos;
                            $vaga_array['empresa']=$vaga->nome_empresa;
                            $vaga_array['cidade']=$vaga->nome_cidade;
                            $vaga_array['estado']=$vaga->uf_estado;
                            $replaces['vagas'][]=$vaga_array;
                    }
            }

           //  Transaction::setLogger(new LoggerTXT('/Tmp/log_grafico.txt'));

            $cursos = Curso::all();
            // print_r($cursos);
            $i=0;
            foreach ($cursos as $curso){
                $criteria = new Criteria;
                $criteria->add(new Filter('id_curso','=',$curso->id));
                $repo=new Repository('CursoVaga');
                $cursosCount = $repo->count($criteria);
                $numeroCursos[$curso->id]['nome'] = $curso->nome;
                $numeroCursos[$curso->id] ['numero']=$cursosCount;


                //print_r($cursosCount);
            }

            $grafico= array();
            $grafico['log']=$numeroCursos[2]['numero'];
            $grafico['adm']=$numeroCursos[3]['numero'];
            $grafico['info']=$numeroCursos[1]['numero'];
            $replaces['grafico']=$grafico;



    }



    catch (Exception $e){
            new Message('error', $e->getMessage());
    }
		
		   
                       
                        
			
	}

        public function show(){
                           
            
                                    
                                 // $card = new Card('Programador', 1);
                                 // $card->add('sao paulo', 'info', 'blablabla');
                                 // $card->show();
                                  
                                  $arr = array(1, 2, 3, 4);
                                  
                                  $grid = new Grid('sm',3);
                                  
                                  $info['Informatica'] = 10;
                                  $info['Logistica'] = 10;
                                  $info['Administracao'] = 10;
                                  
                                  $graf = new Grafico($info);
                                  
                                 $grid->add($graf);
                               //   $graf ->show();
                                  
                                 /*
                                  foreach ($arr as $value){
                                       $panel = new Panel('vaga');
                                       $panel->add($value);
                                       $grid->add($panel);
                                       $grid->addCol('sm', 3);
                                   }
                                   */
                                  $grid->show();
                                  
                                  
                                 
                       }
        }