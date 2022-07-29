<?php

namespace Application\Model;
use Zend\Db\TableGateway\TableGateway;

class UsuarioAtividadeTable
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

	public function getUsuariosAtividadesAtivas()
	{
		$sql = 'select a.* from usuario_atividade a where a.del = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getUsuarioAtividadesAtivasByProjetoByUsuario($projeto_id, $usuario_id)
	{
		$sql = "SELECT id, data_final, DATE_FORMAT(data_final,'%Y') as ano, DATE_FORMAT(data_final,'%m') as mes FROM usuario_atividade where del = 0 and projeto_id = $projeto_id and usuario_id = $usuario_id GROUP BY YEAR(data_final), MONTH(data_final) ORDER BY ano DESC, mes desc";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuarioAtividadesAtivasByProjetoByUsuario1($usuario_id)
	{
		$sql = "SELECT id, data_final, DATE_FORMAT(data_final,'%Y') as ano, DATE_FORMAT(data_final,'%m') as mes FROM usuario_atividade where projeto_id is NULL and del = 0 and usuario_id = $usuario_id GROUP BY YEAR(data_final), MONTH(data_final) ORDER BY ano DESC, mes desc, id desc";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuarioAtividadesAtivasByProjetoByUsuario2($projeto_id, $usuario_id)
	{
		$sql = "SELECT id, data_final, DATE_FORMAT(data_final,'%Y') as ano, DATE_FORMAT(data_final,'%m') as mes FROM usuario_atividade where del = 0 and projeto_id = $projeto_id and usuario_id = $usuario_id GROUP BY YEAR(data_final), MONTH(data_final) ORDER BY ano DESC, mes desc";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuarioLastAtividades()
	{
		$sql = "select * from usuario_atividade where del = 0 order by id desc";
	
		$statement = $this->tableGateway->adapter->query($sql);
		
		return $statement->execute();
	}
	
	public function getUsuarioAtividadeByProjetoByUsuarioByData($projeto_id, $usuario_id, $mes, $ano)
	{
		$sql = "select * from usuario_atividade where projeto_id = $projeto_id AND MONTH(data_final) = $mes AND YEAR(data_final) = $ano and usuario_id = $usuario_id order by id desc limit 1";
	
		$statement = $this->tableGateway->adapter->query($sql);
		$row = $statement->execute();
		
		return $row->current();		
	}
	
	public function getUsuarioAtividade($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveUsuarioAtividade(UsuarioAtividade $usuario_atividade)
	{
		
		$data = array(
				'projeto_id' => $usuario_atividade->projeto_id,
				'usuario_id' => $usuario_atividade->usuario_id,
				'texto' => $usuario_atividade->texto,
				'data_inicial' => date('Y-m-d', strtotime($usuario_atividade->data_inicial)),
				'data_final' => date('Y-m-d', strtotime($usuario_atividade->data_final)),
				'data_abrang_ini' => date('Y-m-d', strtotime($usuario_atividade->data_abrang_ini)),
				'data_abrang_fim' => date('Y-m-d', strtotime($usuario_atividade->data_abrang_fim)),
				'data_lanc' => date('Y-m-d', strtotime($usuario_atividade->data_lanc)),
				'hora_lanc' => $usuario_atividade->hora_lanc,
				'data_relatorio' => date('Y-m-d', strtotime($usuario_atividade->data_relatorio)),
				'ip' => $usuario_atividade->ip,
				'del' => $usuario_atividade->del,
		);


		$id = (int) $usuario_atividade->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuarioAtividade($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_atividade id does not exist');
			}
		}
	}


    public function getAtividadesProjetoUsuario($projeto_id, $usuario_id)
    {
        $sql = "select a.*, b.descricao as atividade from atividade_projeto_usuario a left join atividade_projeto b on b.id = a.atividade_projeto_id where a.del = 0 and a.projeto_id = ".(int)$projeto_id." and usuario_id = ".(int)$usuario_id." order by b.descricao asc";

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

	public function deleteUsuarioAtividade($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}