<?php
	class processDependence {
		private $_db, $_log;
		private $processDependenceId, $fatherId, $chieldId;

		function __construct($_db, $_log, $processDependenceId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($processDependenceId != 0){
				$this->setProcessDependence($processDependenceId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->processDependenceId);
			unset($this->fatherId);
			unset($this->chieldId);
		}

		function setProcessDependence($processDependenceId){
			$response = FALSE;
			$dataProcessDependence = $this->findProcessDependence($processDependenceId);
			if($dataProcessDependence){
				$this->mapProcessDependence($dataProcessDependence);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("processDependenceId"=>$processDependenceId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findProcessDependence($processDependenceId){
			$response = false;
			$sql =  "SELECT * FROM system.process_dependence WHERE process_dependence_id = $processDependenceId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("processDependenceId"=>$processDependenceId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("processDependenceId"=>$processDependenceId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapProcessDependence($rs){
			$this->processDependenceId = $rs['process_dependence_id'];
			$this->fatherId = $rs['father_id'];
			$this->chieldId = $rs['chield_id'];
		}

		public function getProcessDependenceId() { return $this->processDependenceId; }
		public function getFatherId() { return $this->fatherId; }
		public function getChieldId() { return $this->chieldId; }
	}