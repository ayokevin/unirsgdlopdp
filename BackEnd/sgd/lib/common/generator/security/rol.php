<?php
	class rol {
		private $_db, $_log;
		private $rolId, $_status, $rolName, $rolDescription;

		function __construct($_db, $_log, $rolId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($rolId != 0){
				$this->setRol($rolId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->rolId);
			unset($this->_status);
			unset($this->rolName);
			unset($this->rolDescription);
		}

		function setRol($rolId){
			$response = FALSE;
			$dataRol = $this->findRol($rolId);
			if($dataRol){
				$this->mapRol($dataRol);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("rolId"=>$rolId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findRol($rolId){
			$response = false;
			$sql =  "SELECT * FROM security.rol WHERE rol_id = $rolId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("rolId"=>$rolId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("rolId"=>$rolId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapRol($rs){
			$this->rolId = $rs['rol_id'];
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
			$this->rolName = $rs['rol_name'];
			$this->rolDescription = $rs['rol_description'];
		}

		public function getRolId() { return $this->rolId; }
		public function getStatus() { return $this->_status; }
		public function getRolName() { return $this->rolName; }
		public function getRolDescription() { return $this->rolDescription; }
	}