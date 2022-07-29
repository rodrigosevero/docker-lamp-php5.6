<?php

namespace Application\Model;

class AreaFuncaoHistorico
{
    public $id;
    public $area_id;
    public $funcao_id;
    public $colaborador_id;
    public $data;
    public $hora;
    public $usuario_id;
    public $del;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
        $this->funcao_id     = (!empty($data['funcao_id'])) ? $data['funcao_id'] : null;
        $this->colaborador_id     = (!empty($data['colaborador_id'])) ? $data['colaborador_id'] : null;
        $this->data     = (!empty($data['data'])) ? $data['data'] : null;
        $this->hora     = (!empty($data['hora'])) ? $data['hora'] : null;
        $this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
        $this->del = (!empty($data['del'])) ? $data['del'] : null;
    }
}
