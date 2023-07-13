<?php
	class reference {
		private $_db, $_log;
		private $referenceId, $referenceName, $referenceDescription, $referenceTableName, $referenceField;

		function __construct($_db, $_log, $referenceId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($referenceId != 0){
				$this->setReference($referenceId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->referenceId);
			unset($this->referenceName);
			unset($this->referenceDescription);
			unset($this->referenceTableName);
			unset($this->referenceField);
		}

		function setReference($referenceId){
			$response = FALSE;
			$dataReference = $this->findReference($referenceId);
			if($dataReference){
				$this->mapReference($dataReference);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("referenceId"=>$referenceId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findReference($referenceId){
			$response = false;
			$sql =  "SELECT * FROM common.reference WHERE reference_id = $referenceId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("referenceId"=>$referenceId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("referenceId"=>$referenceId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapReference($rs){
			$this->referenceId = $rs['reference_id'];
			$this->referenceName = $rs['reference_name'];
			$this->referenceDescription = $rs['reference_description'];
			$this->referenceTableName = $rs['reference_table_name'];
			$this->referenceField = $rs['reference_field'];
		}

		public function listReference($referenceTableName,$referenceField){
			$response = false;
			$sql =  "SELECT reference_id, reference_name, reference_description, reference_table_name, reference_field ".
			"FROM common.reference ".
			"where reference_table_name ='$referenceTableName' and reference_field ='$referenceField';";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No ahy tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}



		public function getReferenceId() { return $this->referenceId; }
		public function getReferenceName() { return $this->referenceName; }
		public function getReferenceDescription() { return $this->referenceDescription; }
		public function getReferenceTableName() { return $this->referenceTableName; }
		public function getReferenceField() { return $this->referenceField; }
	}