<?php

namespace Application\Model;

class Participante
{
	public $id;
	public $nome;
	public $email;
	public $tel_fixo;
	public $tel_movel;
	public $cpf;
	public $data_nascimento;
	public $tipo_instituicao;
	public $senha;
	public $nivel_escolaridade;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->nome     = (!empty($data['nome'])) ? $data['nome'] : null;
		$this->email     = (!empty($data['email'])) ? $data['email'] : null;
		$this->tel_fixo     = (!empty($data['tel_fixo'])) ? $data['tel_fixo'] : null;
		$this->tel_movel     = (!empty($data['tel_movel'])) ? $data['tel_movel'] : null;
		$this->cpf     = (!empty($data['cpf'])) ? $data['cpf'] : null;
		$this->data_nascimento     = (!empty($data['data_nascimento'])) ? $data['data_nascimento'] : null;
		$this->tipo_instituicao     = (!empty($data['tipo_instituicao'])) ? $data['tipo_instituicao'] : null;
		$this->senha     = (!empty($data['senha'])) ? $data['senha'] : null;
		$this->nivel_escolaridade     = (!empty($data['nivel_escolaridade'])) ? $data['nivel_escolaridade'] : null;
		$this->del     = (!empty($data['del'])) ? $data['del'] : null;

	}
}