<?php

namespace Application\Model;

class Usuario
{
	public $id;
	public $nome;
	public $email;
	public $tel_fixo;
	public $tel_movel;
	public $cpf;
	public $cnpj;
	public $razao_social;
	public $senha;
	public $area_id;
	public $nucleo_id;
	public $area_id2;
	public $vinculo;
    public $curso_estagio;
    public $professor_estagio;
	public $instituicao;
	public $cargo_funcao;
	public $bonificacao_id;
	public $bonificacao_projeto;
	public $bolsa_id;
	public $bolsa_projeto;
	public $unidade_lotacao;
	public $permissao;
	public $del;
	public $atualizado;
	public $data_atualizado;
	public $hora_atualizado;
	public $status;
	public $siape;
	public $data_inatividade;
	public $data_admissao;
	public $status_enquadramento_funcional;
	public $representante_id;
	public $representante_id2;
	public $funcao;
	public $superadmin;
	public $anexo;
	public $termo_autorizacao;
	public $parcelas;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->nome     = (!empty($data['nome'])) ? $data['nome'] : null;
		$this->email     = (!empty($data['email'])) ? $data['email'] : null;
		$this->tel_fixo     = (!empty($data['tel_fixo'])) ? $data['tel_fixo'] : null;
		$this->tel_movel     = (!empty($data['tel_movel'])) ? $data['tel_movel'] : null;
		$this->cpf     = (!empty($data['cpf'])) ? $data['cpf'] : null;
		$this->cnpj     = (!empty($data['cnpj'])) ? $data['cnpj'] : null;
		$this->razao_social     = (!empty($data['razao_social'])) ? $data['razao_social'] : null;
		$this->senha     = (!empty($data['senha'])) ? $data['senha'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
        $this->nucleo_id     = (!empty($data['nucleo_id'])) ? $data['nucleo_id'] : null;
        $this->area_id2     = (!empty($data['area_id2'])) ? $data['area_id2'] : null;
		$this->vinculo     = (!empty($data['vinculo'])) ? $data['vinculo'] : null;
        $this->curso_estagio     = (!empty($data['curso_estagio'])) ? $data['curso_estagio'] : null;
        $this->professor_estagio     = (!empty($data['professor_estagio'])) ? $data['professor_estagio'] : null;
        $this->instituicao     = (!empty($data['instituicao'])) ? $data['instituicao'] : null;
		$this->cargo_funcao     = (!empty($data['cargo_funcao'])) ? $data['cargo_funcao'] : null;
		$this->bonificacao_id     = (!empty($data['bonificacao_id'])) ? $data['bonificacao_id'] : null;
		$this->bonificacao_projeto     = (!empty($data['bonificacao_projeto'])) ? $data['bonificacao_projeto'] : null;
		$this->bolsa_id     = (!empty($data['bolsa_id'])) ? $data['bolsa_id'] : null;
		$this->bolsa_projeto     = (!empty($data['bolsa_projeto'])) ? $data['bolsa_projeto'] : null;
		$this->unidade_lotacao     = (!empty($data['unidade_lotacao'])) ? $data['unidade_lotacao'] : null;
		$this->permissao     = (!empty($data['permissao'])) ? $data['permissao'] : null;
		$this->del     = (!empty($data['del'])) ? $data['del'] : null;
		$this->atualizado     = (!empty($data['atualizado'])) ? $data['atualizado'] : null;
		$this->data_atualizado     = (!empty($data['data_atualizado'])) ? $data['data_atualizado'] : null;
		$this->hora_atualizado     = (!empty($data['hora_atualizado'])) ? $data['hora_atualizado'] : null;
		$this->status     = (!empty($data['status'])) ? $data['status'] : null;
		$this->siape     = (!empty($data['siape'])) ? $data['siape'] : null;
		$this->data_inatividade     = (!empty($data['data_inatividade'])) ? $data['data_inatividade'] : null;
		$this->data_admissao     = (!empty($data['data_admissao'])) ? $data['data_admissao'] : null;
		$this->status_enquadramento_funcional = (!empty($data['status_enquadramento_funcional'])) ? $data['status_enquadramento_funcional'] : null;
        $this->representante_id = (!empty($data['representante_id'])) ? $data['representante_id'] : null;
        $this->representante_id2 = (!empty($data['representante_id2'])) ? $data['representante_id2'] : null;
        $this->funcao = (!empty($data['funcao'])) ? $data['funcao'] : null;
		$this->superadmin     = (!empty($data['superadmin'])) ? $data['superadmin'] : null;
		$this->arquivo     = (!empty($data['arquivo'])) ? $data['arquivo'] : null;
		$this->termo_autorizacao     = (!empty($data['termo_autorizacao'])) ? $data['termo_autorizacao'] : null;
		$this->parcelas     = (!empty($data['parcelas'])) ? $data['parcelas'] : null;
	}
}