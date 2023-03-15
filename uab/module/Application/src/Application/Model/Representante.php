<?php

namespace Application\Model;

class Representante
{
	public $id;
	public $nome;
	public $email;
	public $cpf;
	public $nome_cargo;
	public $funcao_confianca;
	public $telefone;
	public $orgao;
	public $setor_lotacao;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->nome     = (!empty($data['nome'])) ? $data['nome'] : null;
		$this->email     = (!empty($data['email'])) ? $data['email'] : null;
		$this->cpf     = (!empty($data['cpf'])) ? $data['cpf'] : null;
		$this->nome_cargo = (!empty($data['nome_cargo'])) ? $data['nome_cargo'] : null;
		$this->funcao_confianca = (!empty($data['funcao_confianca'])) ? $data['funcao_confianca'] : null;
		$this->telefone     = (!empty($data['telefone'])) ? $data['telefone'] : null;
		$this->orgao = (!empty($data['orgao'])) ? $data['orgao'] : null;		
		$this->setor_lotacao = (!empty($data['setor_lotacao'])) ? $data['setor_lotacao'] : null;		
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}
