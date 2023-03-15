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
	public $vinculo;
	public $instituicao;
	public $curso;
	public $polo;
	public $tipo_processo_seletivo;
	public $nu_edital;
	public $cargo_funcao;
	public $unidade_lotacao;
	public $permissao;
	public $del;
	public $atualizado;
	public $data_atualizado;
	public $hora_atualizado;
	public $status;
	public $data_inatividade;
	public $data_admissao;
	public $status_enquadramento_funcional;
	public $funcao;
	public $superadmin;
	public $representante_id;
	public $is_representante;
	public $arquivo;

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
		$this->vinculo     = (!empty($data['vinculo'])) ? $data['vinculo'] : null;
		$this->instituicao     = (!empty($data['instituicao'])) ? $data['instituicao'] : null;
		$this->curso     = (!empty($data['curso'])) ? $data['curso'] : null;
		$this->polo     = (!empty($data['polo'])) ? $data['polo'] : null;
		$this->tipo_processo_seletivo     = (!empty($data['tipo_processo_seletivo'])) ? $data['tipo_processo_seletivo'] : null;
		$this->nu_edital     = (!empty($data['nu_edital'])) ? $data['nu_edital'] : null;
		$this->cargo_funcao     = (!empty($data['cargo_funcao'])) ? $data['cargo_funcao'] : null;
		$this->unidade_lotacao     = (!empty($data['unidade_lotacao'])) ? $data['unidade_lotacao'] : null;
		$this->permissao     = (!empty($data['permissao'])) ? $data['permissao'] : null;
		$this->del     = (!empty($data['del'])) ? $data['del'] : null;
		$this->atualizado     = (!empty($data['atualizado'])) ? $data['atualizado'] : null;
		$this->data_atualizado     = (!empty($data['data_atualizado'])) ? $data['data_atualizado'] : null;
		$this->hora_atualizado     = (!empty($data['hora_atualizado'])) ? $data['hora_atualizado'] : null;
		$this->status     = (!empty($data['status'])) ? $data['status'] : null;
		$this->data_inatividade     = (!empty($data['data_inatividade'])) ? $data['data_inatividade'] : null;
		$this->data_admissao     = (!empty($data['data_admissao'])) ? $data['data_admissao'] : null;
		$this->status_enquadramento_funcional = (!empty($data['status_enquadramento_funcional'])) ? $data['status_enquadramento_funcional'] : null;
		$this->funcao = (!empty($data['funcao'])) ? $data['funcao'] : null;	
		$this->superadmin     = (!empty($data['superadmin'])) ? $data['superadmin'] : null;
        $this->representante_id     = (!empty($data['representante_id'])) ? $data['representante_id'] : null;
        $this->is_representante     = (!empty($data['is_representante'])) ? $data['is_representante'] : null;
        $this->arquivo     = (!empty($data['arquivo'])) ? $data['arquivo'] : null;
	}
}