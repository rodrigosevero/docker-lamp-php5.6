<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class FeriasTable
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

    public function getFeriasAtivas()
    {
        $sql = 'select a.*, b.nome as colaborador from ferias a left join usuario b on b.id = a.usuario_id where a.del = 0';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }

//    public function getAreaPorArea($id)
//    {
//        $sql = 'select a.* from area a where a.del = 0 and a.id = "'.$id.'" order by a.descricao';
//        $statement = $this->tableGateway->adapter->query($sql);
//        return $statement->execute();
//    }
//
//    public function getArea1($id)
//    {
//        $id  = (int) $id;
//        $sql = 'select a.* from area a where a.del = 0 and a.id = "'.$id.'" order by a.descricao';
//        $statement = $this->tableGateway->adapter->query($sql);
//        return $statement->execute();
//    }

    public function getFerias($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFerias(Ferias $ferias)
    {

        $data = array(
            'usuario_id' => $ferias->usuario_id,
            'inicio' => $ferias->inicio,
            'fim' => $ferias->fim,
            'pa_inicio' => $ferias->inicio,
            'pa_fim' => $ferias->fim,
            'del' => $ferias->del,
        );

        $id = (int) $ferias->id;
        if ($id == 0) {
            echo 'ok';
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
            return $id;
        } else {
            if ($this->getFerias($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('area id does not exist');
            }
        }
    }

    public function deleteFerias($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}