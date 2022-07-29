<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AreaFuncaoHistoricoTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAreaFuncoes()
    {
        $sql = 'select a.*, b.descricao as funcao, c.nome as colaborador 
				from area_funcao a 
				left join funcao b on b.id = a.funcao_id 
				left join usuario c on c.id = a.colaborador_id 
				where a.del = 0 ';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }

    public function getFuncaoColaborador($usuario_id)
    {
        $sql = 'select a.*, b.descricao as funcao, c.nome as colaborador 
        from area_funcao a 
        left join funcao b on b.id = a.funcao_id 
        left join usuario c on c.id = a.colaborador_id 
        where a.del = 0 and a.colaborador_id = ' . $usuario_id. ' order by id desc limit 1 ';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }

    public function getAreaFuncao($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAreaFuncaoHistorico(AreaFuncaoHistorico $area_funcao_historico)
    {
        $data = array(
            'area_id' => $area_funcao_historico->area_id,
            'funcao_id' => $area_funcao_historico->funcao_id,
            'colaborador_id' => $area_funcao_historico->colaborador_id,
            'data' => $area_funcao_historico->data,
            'hora' => $area_funcao_historico->hora,
            'usuario_id' => $area_funcao_historico->usuario_id,
            'del' => $area_funcao_historico->del
        );

        $this->tableGateway->insert($data);
        $id = $this->tableGateway->getLastInsertValue();
        return $id;
    }

    public function saveColaborador($colaborador_id, $funcao_id, $funcao_id2)
    {
        $data = array(
            'colaborador_id' => NULL
        );
        $this->tableGateway->update($data, array('id' => $funcao_id2));

        $data1 = array(
            'colaborador_id' => $colaborador_id
        );

        $this->tableGateway->update($data1, array('id' => $funcao_id));
    }

    public function deleteFuncao($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
