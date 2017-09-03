<?php
use Sheep\Control\Page;
use Sheep\Widgets\Container\Panel;
use Sheep\Database\Transaction;
use Sheep\Widgets\Dialog\Message;
class CardList extends Page 
{
	public function __construct(){
		
			parent::__construct();
			require_once 'Lib/Twig/Autoloader.php'; 
			Twig_Autoloader::register(); 
			
			$loader = new Twig_Loader_Filesystem('App/Resources'); 
			$twig = new Twig_Environment($loader); 
			$template = $twig->loadTemplate('card.html'); 
			
			
			try {
				$replaces = array();
				Transaction::open('trunfo');
				
				$vagas = Vaga::all();
				
				
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
				$grafico= array();
				$grafico['log']=10;
				$grafico['adm']=10;
				$grafico['info']=10;
				$replaces['grafico']=$grafico;
				//print_r($replaces);
				$content = $template->render($replaces);
				
                             //   $template2 = $twig->loadTemplate('principal.html');
                              //  $replaces2 = array();
                               // $replaces2['conteudo']=$content1;
                                //$content = $template2->render($replaces2);
			}
			
			
			
			catch (Exception $e){
				new Message('error', $e->getMessage());
			}
		
		   
			parent::add($content);
			
	}
	

}