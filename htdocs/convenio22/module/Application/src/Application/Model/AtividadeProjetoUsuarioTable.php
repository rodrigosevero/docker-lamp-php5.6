<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AtividadeProjetoUsuarioTable
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

    public function getAtividadesProjetoUsuario($projeto_id)
    {
        $sql = "select * from atividade_projeto_usuario where del = 0 and projeto_id = ".(int)$projeto_id." order by id desc";

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

   
}