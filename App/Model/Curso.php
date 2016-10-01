<?php

	use Sheep\Database\Record;
	use Sheep\Database\Criteria;
	use Sheep\Database\Repository;
	use Sheep\Database\Filter;
	
	class Curso extends Record{
		const TABLENAME = 'curso';
	
	//Retorna todos as Vaga que o curso estiver vinculado
		public function getVagas(){
			$vagas = array();
			$criteria=new Criteria;
			$criteria->add(new Filter('codigo_curso','=',$this->id));
			$repo= new Repository('CursoVaga');
			$vinculos = $repo->load($criteria);
			if ($vinculos){
				foreach ($vinculos as $vinculo){
					$vagas[] = new Curso($vinculo->id_curso);
				}
			}
			return $vagas;
		}
		
	
	}