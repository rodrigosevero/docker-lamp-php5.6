<?php

namespace Application\Model;

class AreaFuncao
{
    public $id;
    public $area_id;
    public $funcao_id;
    public $colaborador_id;
    public $del;

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
        $this->funcao_id     = (!empty($data['funcao_id'])) ? $data['funcao_id'] : null;
        $this->colaborador_id     = (!empty($data['colaborador_id'])) ? $data['colaborador_id'] : null;
        $this->del = (!empty($data['del'])) ? $data['del'] : null;
    }
}