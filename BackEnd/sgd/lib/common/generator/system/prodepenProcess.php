<?php
	class prodepenProcess {
		private $_db, $_log;
		private $prodepenProcessId, $_processDependence, $_process;

		function __construct($_db, $_log, $prodepenProcessId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($prodepenProcessId != 0){
				$this->setProdepenProcess($prodepenProcessId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->prodepenProcessId);
			unset($this->_processDependence);
			unset($this->_process);
		}

		function setProdepenProcess($prodepenProcessId){
			$response = FALSE;
			$dataProdepenProcess = $this->findProdepenProcess($prodepenProcessId);
			if($dataProdepenProcess){
				$this->mapProdepenProcess($dataProdepenProcess);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("prodepenProcessId"=>$prodepenProcessId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findProdepenProcess($prodepenProcessId){
			$response = false;
			$sql =  "SELECT * FROM system.prodepen_process WHERE prodepen_process_id = $prodepenProcessId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("prodepenProcessId"=>$prodepenProcessId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("prodepenProcessId"=>$prodepenProcessId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapProdepenProcess($rs){
			$this->prodepenProcessId = $rs['prodepen_process_id'];
			$this->_processDependence = new processDependence($this->_db, $this->_log, $rs['process_dependence_id']);
			$this->_process = new process($this->_db, $this->_log, $rs['process_id']);
		}

		public function getProdepenProcessId() { return $this->prodepenProcessId; }
		public function getProcessDependence() { return $this->_processDependence; }
		public function getProcess() { return $this->_process; }
	}