<?php
	use Sheep\Control\Page;
	use Sheep\Database\Transaction;
	use Sheep\Database\Criteria;
	use Sheep\Database\Repository;
	use Sheep\Database\Filter;
	
	class ModelTest1 extends Page {
		public function show(){
			try{
				Transaction::open('trunfo');
			
				/*
				$Estado = array();
				$criteria=new Criteria;
				$criteria->add(new Filter('uf','=',"SP"));
				$repo= new Repository('Estado');
				$Estado = $repo->load($criteria);
				//$vaga=cursoVaga::find(1);
				print_r($vagas) . '<br>';
				//$pais = Pais::find(1);
				//$cidade = Cidade::find(1);
				//print($cidade->name);
				//print($cidade->estado->nome);
				//print($cidade->name_estado);
				*/
				//$usuario=Usuario::all();
				$cidade = Cidade::all();
				$curso = Curso::all();
				print_r($curso);
				print("Tésté acentùação");
				
			}
			catch (Exception $e){
				echo $e->getMessage();
			}
			
			
		}
	}
	
	
	
				