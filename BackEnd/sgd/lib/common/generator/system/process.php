<?php
	class process {
		private $_db, $_log;
		private $processId, $_userSec, $_department, $processName, $processOrder, $processDescription, $_status;

		function __construct($_db, $_log, $processId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($processId != 0){
				$this->setProcess($processId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->processId);
			unset($this->_userSec);
			unset($this->_department);
			unset($this->processName);
			unset($this->processOrder);
			unset($this->processDescription);
			unset($this->_status);
		}

		function setProcess($processId){
			$response = FALSE;
			$dataProcess = $this->findProcess($processId);
			if($dataProcess){
				$this->mapProcess($dataProcess);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("processId"=>$processId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findProcess($processId){
			$response = false;
			$sql =  "SELECT * FROM system.process WHERE process_id = $processId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("processId"=>$processId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("processId"=>$processId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapProcess($rs){
			$this->processId = $rs['process_id'];
			$this->_userSec = new userSec($this->_db, $this->_log, $rs['user_sec_id']);
			$this->_department = new department($this->_db, $this->_log, $rs['department_id']);
			$this->processName = $rs['process_name'];
			$this->processOrder = $rs['process_order'];
			$this->processDescription = $rs['process_description'];
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
		}

		public function getProcessId() { return $this->processId; }
		public function getUserSec() { return $this->_userSec; }
		public function getDepartment() { return $this->_department; }
		public function getProcessName() { return $this->processName; }
		public function getProcessOrder() { return $this->processOrder; }
		public function getProcessDescription() { return $this->processDescription; }
		public function getStatus() { return $this->_status; }
	}