<?php
 
	use Sheep\Database\Record;
	use Sheep\Database\Criteria;
	use Sheep\Database\Repository;
	use Sheep\Database\Filter;
	
	class Vaga extends Record {
		const TABLENAME = 'vaga';
		
		
		//Adiciona um Curso para a Vaga
		
		public function addCurso(Curso $curso){
			$cv = new CursoVaga;
			$cv->id_curso = $curso->id;
			$cv->id_vaga = $this->id;
			$cv->store();
		}
		//Deleta todos os Cursos que a Vaga estiver vinculada
		public function delCursos(){
			$criteria=new Criteria;
			$criteria->add(new Filter('id_vaga','=', $this->id));
			$repo= new Repository('CursoVaga');
			return $repo->delete($criteria);
		}
		
		//Retorna todos os Cursos que a Vaga estiver vinculada
		public function getCursos(){
			$cursos = array();
			$criteria=new Criteria;
			$criteria->add(new Filter('id_vaga','=',$this->id));
			$repo= new Repository('CursoVaga');
			$vinculos = $repo->load($criteria);
			if ($vinculos){
				foreach ($vinculos as $vinculo){
					$cursos[] = new Curso($vinculo->id_curso);
				}
			}
			return $cursos;
		}
		
	
		
		//Retorna todos os Ids de Curso que a Vaga estiver vinculada
		public function getIdsCurso(){
			$cursos_ids = array();
			$cursos = $this->getCursos();
			if ($cursos){
				foreach($cursos as $curso){
					$cursos_ids[]=$curso->id;
				}
			}
			return $cursos_ids;
		}
		
		//Retorna empresa vinculada
		public function get_empresa(){
			return new Empresa($this->id_empresa);
		}
		//Retorna nome da empresa vinculada
		public function get_nome_empresa(){
			return (new Empresa($this->id_empresa))->nome_fantasia;
		}
			
		public function get_nome_cidade()
		{
				$empresa = new Empresa($this->id_empresa);
				$this->cidade = new Cidade($empresa->id_cidade);
			
			return $this->cidade->nome;
		}
		
		public function get_email()
		{
				$empresa = new Empresa($this->id_empresa);
			
			return $empresa->email;
		}
		
		public function get_uf_estado()
		{
				$empresa = new Empresa($this->id_empresa);
				$cidade = new Cidade($empresa->id_cidade);
			    $this->estado = new Estado($cidade->id_estado);
			return $this->estado->uf;
		}
		
		public function get_telefone()
		{
				$empresa = new Empresa($this->id_empresa);
			
			return $empresa->telefone_1;
		}
	}