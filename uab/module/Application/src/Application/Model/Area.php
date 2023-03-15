<?php

namespace Application\Model;

class Area
{
	public $id;
	public $descricao;
	public $meta;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->meta     = (!empty($data['meta'])) ? $data['meta'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}