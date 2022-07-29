<?php

namespace Application\Model;

class Ferias
{
    public $id;
    public $usuario_id;
    public $inicio;
    public $fim;
    public $pa_inicio;
    public $pa_fim;
    public $del;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->usuario_id = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
        $this->inicio     = (!empty($data['inicio'])) ? $data['inicio'] : null;
        $this->fim = (!empty($data['fim'])) ? $data['fim'] : null;
        $this->pa_inicio = (!empty($data['pa_inicio'])) ? $data['pa_inicio'] : null;
        $this->pa_fim = (!empty($data['pa_fim'])) ? $data['pa_fim'] : null;
        $this->del = (!empty($data['del'])) ? $data['del'] : null;
    }
}