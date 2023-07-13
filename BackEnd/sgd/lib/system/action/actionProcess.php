<?php
require_once ('C://xampp/htdocs/sgd/lib/system/action/action.php');
require_once ('C://xampp/htdocs/sgd/lib/system/process/process.php');
	class actionProcess {
		private $_db, $_log;
		private $_process, $_action;

		function __construct($_db, $_log, $processId=0, $actionId=0){
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

		public function insertActionProcess($_process,$_action){
			$response = false;
			$sql =  "INSERT INTO system.action_process (process_id, action_id) ".
					"SELECT $_process, $_action ".
					"WHERE NOT EXISTS ( ".
					"SELECT 1 FROM system.action_process ".
					"WHERE process_id = $_process AND action_id = $_action ".
					") returning process_id,action_id;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setActionProcess($rs[0]['process_id'],$rs[0]['action_id']):true;
				$arrLog = array("input"=>array( "process_id"=>$_process,
												"action_id"=>$_action
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "process_id"=>$_process,
												"action_id"=>$_action
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateActionProcess($_processOld,$_actionOld, $_processNew,$_actionNew){
			$response = false;
			$sql =  "UPDATE system.action_process ".
					"SET process_id = $_processNew, action_id = $_actionNew ".
					"WHERE process_id = $_processOld AND action_id = $_actionOld ".
					"AND NOT EXISTS ( ".
					"SELECT 1 FROM system.action_process ".
					"WHERE process_id = $_processNew AND action_id = $_actionNew ) returning process_id, action_id"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setActionProcess($rs[0]['process_id'],$rs[0]['action_id']):true;
				$arrLog = array("input"=>array( "_processOld"=>$_processOld,
												"_actionOld"=>$_actionOld,
												"_processNew"=>$_processNew,
												"_actionNew"=>$_actionNew
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_processOld"=>$_processOld,
												"_actionOld"=>$_actionOld,
												"_processNew"=>$_processNew,
												"_actionNew"=>$_actionNew
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function deleteActionProcess($_process,$_action){
			$response = false;
			$sql =  "DELETE FROM system.action_process ".
					"WHERE process_id = $_process AND action_id = $_action;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setActionProcess($rs[0]['process_id'],$rs[0]['aaction_id']):true;
				$arrLog = array("input"=>array( "_process"=>$_process,
												"_action"=>$_action
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_process"=>$_process,
												"_action"=>$_action
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listActionProcess($clientId,$processId){
			$response = false;
			$sql =  "SELECT ap.process_id, ap.action_id, a.action_name, ps.process_name ".
					"FROM system.action_process ap,system.action a,common.department d ,system.process ps ".
					"where ap.action_id = a.action_id and ps.department_id = d.department_id and ap.process_id = ps.process_id ".
					"and d.client_id = $clientId and ps.process_id = $processId;"; 
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}



		public function getProcess() { return $this->_process; }
		public function getAction() { return $this->_action; }
	}