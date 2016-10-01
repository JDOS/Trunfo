<?php
	use Sheep\Database\Record;

	class Estado extends Record {
		const TABLENAME = 'estado';
		
		//função retorna id do Pais qnd estado->pais
		public function get_pais(){
			return new Pais($this->pais);
		}
		//retorna nome do Pais qnd estado_nome_pais
		public function get_nome_pais(){
			return (new Pais($this->pais))->nome;
		}
		
	}