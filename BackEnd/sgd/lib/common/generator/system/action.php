<?php
	class action {
		private $_db, $_log;
		private $actionId, $_status, $actionName, $actionDescription, $actionCreatedAt, $actionUpdatedAt, $_department;

		function __construct($_db, $_log, $actionId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($actionId != 0){
				$this->setAction($actionId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->actionId);
			unset($this->_status);
			unset($this->actionName);
			unset($this->actionDescription);
			unset($this->actionCreatedAt);
			unset($this->actionUpdatedAt);
			unset($this->_department);
		}

		function setAction($actionId){
			$response = FALSE;
			$dataAction = $this->findAction($actionId);
			if($dataAction){
				$this->mapAction($dataAction);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("actionId"=>$actionId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findAction($actionId){
			$response = false;
			$sql =  "SELECT * FROM system.action WHERE action_id = $actionId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("actionId"=>$actionId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("actionId"=>$actionId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapAction($rs){
			$this->actionId = $rs['action_id'];
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
			$this->actionName = $rs['action_name'];
			$this->actionDescription = $rs['action_description'];
			$this->actionCreatedAt = $rs['action_created_at'];
			$this->actionUpdatedAt = $rs['action_updated_at'];
			$this->_department = new department($this->_db, $this->_log, $rs['department_id']);
		}

		public function getActionId() { return $this->actionId; }
		public function getStatus() { return $this->_status; }
		public function getActionName() { return $this->actionName; }
		public function getActionDescription() { return $this->actionDescription; }
		public function getActionCreatedAt() { return $this->actionCreatedAt; }
		public function getActionUpdatedAt() { return $this->actionUpdatedAt; }
		public function getDepartment() { return $this->_department; }
	}