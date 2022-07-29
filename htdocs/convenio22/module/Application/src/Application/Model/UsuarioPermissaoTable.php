<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UsuarioPermissaoTable
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
	
	public function getUsuarioPermissao($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveUsuarioPermissao(UsuarioPermissao $usuario_permissao)
	{
		
		$data = array(				
				'usuario_id' => $usuario_permissao->usuario_id,
				'permissao_id' => $usuario_permissao->permissao_id,				
		);


		$id = (int) $usuario_permissao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuarioPermissao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_atividade id does not exist');
			}
		}
	}
    

	public function deleteUsuarioAtividade($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}