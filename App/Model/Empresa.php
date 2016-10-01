<?php

	use Sheep\Database\Record;
	use Sheep\Database\Criteria;
	use Sheep\Database\Repository;
	use Sheep\Database\Filter;
	
	class Empresa extends Record{
		const TABLENAME = 'empresa';
		
			/**
		 * Retorna o nome da cidade.
		 * Executado sempre se for acessada a propriedade "->nome_cidade"
		 */
		public function get_nome_cidade()
		{
			if (empty($this->cidade))
				$this->cidade = new Cidade($this->id_cidade);
			
			return $this->cidade->nome;
		}
		
		//Retorna todos as vagas que a empresa disponibiliza 
		public function get_vagas(){
			$vagas = array();
			$criteria=new Criteria;
			$criteria->add(new Filter('id_empresa','=',$this->id));
			$repo= new Repository('Vaga');
			$vinculos = $repo->load($criteria);
			if ($vinculos){
				foreach ($vinculos as $vinculo){
					$vagas[] = new Vaga($vinculo->id);
				}
			}
			return $vagas;
		}
	}