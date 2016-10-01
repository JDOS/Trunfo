<?php
 
	use Sheep\Database\Record;
	use Sheep\Database\Criteria;
	use Sheep\Database\Repository;
	use Sheep\Database\Filter;
	
	class Usuario extends Record {
		const TABLENAME = 'usuario';
		//Adiciona um Curso para o Usuario
		public function addCurso(Curso $curso){
			$iu = new InfoUsuario;
			$iu->id_usuario = $this->id;
			$iu->id_curso = $curso->id;
		}
		//Deleta todos os cursos que o usuario estiver vinculado
		public function delCursos(){
			$criteria=new Criteria;
			$criteria->add(new Filter('id_usuario','=', $this->id));
			$repo= new Repository('InfoUsuario');
			return $repo->delete($criteria);
		}
		
		//Retorna todos os Cursos que o usuario estiver vinculado
		public function getCursos(){
			$cursos = array();
			$criteria=new Criteria;
			$criteria->add(new Filter('id_usuario','=',$this->id));
			$repo= new Repository('InfoUsuario');
			$vinculos = $repo->load($criteria);
			if ($vinculos){
				foreach ($vinculos as $vinculo){
					$cursos[] = new Curso($vinculo->id_curso);
				}
			}
			return $cursos;
		}
		//Retorna todos os Ids de Curso que o Usuário estiver cadastrado
		public function getIdsCurso(){
			$cursos_ids = array();
			$cursos = $this->getCursos();
			if ($cursos){
				foreach($cursos as $curso){
					$cursos_ids[]=$curso->id;
				}
			}
		}
		
	}