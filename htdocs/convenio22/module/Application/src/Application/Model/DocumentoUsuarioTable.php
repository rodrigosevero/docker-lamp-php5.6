<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class DocumentoUsuarioTable
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


    public function getDocumentosPorUsuario($id)
    {
        $sql = 'select a.* from documentos_usuario a where a.usuario_id = "'.$id.'" order by id desc';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }    
	
	public function getDocumento($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveDocumento(DocumentoUsuario $documento)
	{
		$data = array(
				'tipo' => $documento->tipo,
				'arquivo' => $documento->arquivo,
				'usuario_id' => $documento->usuario_id,
				'usuario_upload_id' => $documento->usuario_upload_id,
				'data' => $documento->data,
				'hora' => $documento->hora,
		);

		$id = (int) $documento->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getDocumento($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('area id does not exist');
			}
		}
	}

	public function deleteDocumento($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}