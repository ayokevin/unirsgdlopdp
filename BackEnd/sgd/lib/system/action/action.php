<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
require_once ('C://xampp/htdocs/sgd/lib/common/department/department.php');
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
			$this->_status = new reference($this->_db, $this->_log, $rs['status_id']);
			$this->actionName = $rs['action_name'];
			$this->actionDescription = $rs['action_description'];
			$this->actionCreatedAt = $rs['action_created_at'];
			$this->actionUpdatedAt = $rs['action_updated_at'];
			$this->_department = new department($this->_db, $this->_log, $rs['department_id']);
		}

		public function listAction($clientId){
			$response = false;
			if($clientId===''){
				$sql =  "SELECT a.action_id, a.status_id, r.reference_name , a.action_name, a.action_description, a.action_created_at, a.action_updated_at, a.department_id, d.department_name ".
						"FROM system.action a,common.department d,common.client c,common.reference r ".
						"where a.department_id = d.department_id and d.client_id = c.client_id and a.status_id = r.reference_id ".
						"order by 1 asc;";
			}else{
				$sql =  "SELECT a.action_id, a.status_id, r.reference_name , a.action_name, a.action_description, a.action_created_at, a.action_updated_at, a.department_id, d.department_name ".
						"FROM system.action a,common.department d,common.client c ,common.reference r ".
						"where a.department_id = d.department_id and d.client_id = c.client_id and c.client_id = $clientId and a.status_id = r.reference_id ".
						"order by 1 asc;";
			}
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function listActionReference($clientId){
			$response = false;
			$sql =  "SELECT a.action_id, a.action_name, a.action_description ".
					"FROM system.action a,common.department d,common.client c ".
					"where a.department_id = d.department_id and d.client_id = c.client_id and c.client_id = $clientId ".
					"order by 1 asc;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = true;
				$arrLog = array("input"=>array( "data"=> $rs),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"data"=> 'No se encuentra la tabla'),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $rs;
		}

		public function insertAction($_status,$actionName,$actionDescription,$_department){
			$response = false;
			$sql =  "INSERT INTO system.action ".
			"(status_id, action_name, action_description, action_created_at, action_updated_at, department_id) ".
			"VALUES($_status, '$actionName', '$actionDescription', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, $_department) ".
			"returning action_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setAction($rs[0]['action_id']):true;
				$arrLog = array("input"=>array( "action_id"=> $rs[0]['action_id'],
												"_status"=>$_status,
												"actionName"=>$actionName,
												"actionDescription"=>$actionDescription,
												"_department"=>$_department
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "_status"=>$_status,
												"actionName"=>$actionName,
												"actionDescription"=>$actionDescription,
												"_department"=>$_department
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateAction($actionId,$_status,$actionName,$actionDescription,$_department){
			$response = false;
			$sql =  "UPDATE system.action ".
					"SET status_id=$_status, action_name='$actionName', action_description='$actionDescription', action_updated_at=CURRENT_TIMESTAMP, department_id=$_department ".
					"WHERE action_id=$actionId returning action_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setAction($rs[0]['action_id']):true;
				$arrLog = array("input"=>array( "action_id"=> $actionId,
												"status_id"=>$_status,
												"actionName"=>$actionName,
												"actionDescription"=>$actionDescription,
												"_department"=>$_department
											),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"action_id"=> $actionId,
												"status_id"=>$_status,
												"actionName"=>$actionName,
												"actionDescription"=>$actionDescription,
												"_department"=>$_department
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function getActionId() { return $this->actionId; }
		public function getStatus() { return $this->_status; }
		public function getActionName() { return $this->actionName; }
		public function getActionDescription() { return $this->actionDescription; }
		public function getActionCreatedAt() { return $this->actionCreatedAt; }
		public function getActionUpdatedAt() { return $this->actionUpdatedAt; }
		public function getDepartment() { return $this->_department; }
	}