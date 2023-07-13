<?php
	class actionProcess {
		private $_db, $_log;
		private $_process, $_action;

		function __construct($_db, $_log, $processId, $actionId){
			$this->_db = $_db;
			$this->_log = $_log;
			if($processId != 0 && $actionId != 0){
				$this->setActionProcess($processId, $actionId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->_process);
			unset($this->_action);
		}

		function setActionProcess($processId, $actionId){
			$response = FALSE;
			$dataActionProcess = $this->findActionProcess($processId, $actionId);
			if($dataActionProcess){
				$this->mapActionProcess($dataActionProcess);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("processId"=>$processId, "actionId"=>$actionId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findActionProcess($processId, $actionId){
			$response = false;
			$sql =  "SELECT * FROM system.action_process WHERE process_id = $processId AND action_id = $actionId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("processId"=>$processId, "actionId"=>$actionId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("processId"=>$processId, "actionId"=>$actionId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapActionProcess($rs){
			$this->_process = new process($this->_db, $this->_log, $rs['process_id']);
			$this->_action = new action($this->_db, $this->_log, $rs['action_id']);
		}

		public function getProcess() { return $this->_process; }
		public function getAction() { return $this->_action; }
	}