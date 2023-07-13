<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
require_once ('C://xampp/htdocs/sgd/lib/common/department/department.php');
require_once ('C://xampp/htdocs/sgd/lib/security/userSec/userSec.php');
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
			$this->_status = new reference($this->_db, $this->_log, $rs['status_id']);
		}

		public function insertProcess($userSecId,$departmentId,$processName,$processOrder,$processDescription,$_statusId){
			$response = false;
			$sql =  "INSERT INTO system.process ".
			"(user_sec_id, department_id, process_name, process_order, process_description, status_id) ".
			"VALUES($userSecId, $departmentId, '$processName', $processOrder, '$processDescription', $_statusId) ".
			"returning process_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setProcess($rs[0]['process_id']):true;
				$arrLog = array("input"=>array( "processId"=> $rs[0]['process_id'],
												"userSecId"=>$userSecId,
												"departmentId"=>$departmentId,
												"processName"=>$processName,
												"processOrder"=>$processOrder,
												"processDescription"=>$processDescription,
												"_statusId"=>$_statusId
											),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "userSecId"=>$userSecId,
												"departmentId"=>$departmentId,
												"processName"=>$processName,
												"processOrder"=>$processOrder,
												"processDescription"=>$processDescription,
												"_statusId"=>$_statusId
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateProcess($processId,$userSecId,$departmentId,$processName,$processOrder,$processDescription,$_statusId){
			$response = false;
			$sql =  "UPDATE system.process ".
			"SET user_sec_id=$userSecId, department_id=$departmentId, process_name='$processName', process_order=$processOrder, process_description='$processDescription', status_id=$_statusId ".
			"WHERE process_id=$processId returning process_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setProcess($rs[0]['process_id']):true;
				$arrLog = array("input"=>array( "processId"=> $rs[0]['process_id'],
												"userSecId"=>$userSecId,
												"departmentId"=>$departmentId,
												"processName"=>$processName,
												"processOrder"=>$processOrder,
												"processDescription"=>$processDescription,
												"_statusId"=>$_statusId
											),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"userSecId"=>$userSecId,
												"departmentId"=>$departmentId,
												"processName"=>$processName,
												"processOrder"=>$processOrder,
												"processDescription"=>$processDescription,
												"_statusId"=>$_statusId
											),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listProcess($clientId){
			$response = false;
			if($clientId===''){
				$sql =  "select p.process_id, p.user_sec_id, us.user_first_name,us.user_last_name,us.user_email, ".
						"p.department_id ,d.department_name ,p.process_name,p.process_order,p.process_description,p.status_id ,r.reference_name ".
						"from system.process p ,security.user_sec us,common.reference r,common.department d ".
						"where p.user_sec_id = us.user_sec_id ".
						"and p.status_id = r.reference_id ".
						"and p.department_id = d.department_id ".
						"order by 1 asc;";
			}else{
				$sql =  "select p.process_id, p.user_sec_id, us.user_first_name,us.user_last_name,us.user_email, ".
						"p.department_id ,d.department_name ,p.process_name,p.process_order,p.process_description,p.status_id ,r.reference_name ".
						"from system.process p ,security.user_sec us,common.reference r,common.department d ".
						"where p.user_sec_id = us.user_sec_id ".
						"and p.status_id = r.reference_id ".
						"and p.department_id = d.department_id ".
						"and us.client_id =$clientId ".
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

		public function listProcessReference($clientId){
			$response = false;
			$sql =  "select p.process_id, p.process_name, p.process_description ".
					"from system.process p ,security.user_sec us,common.department d ".
					"where p.user_sec_id = us.user_sec_id ".
					"and p.department_id = d.department_id ".
					"and us.client_id =$clientId ".
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

		public function getProcessId() { return $this->processId; }
		public function getUserSec() { return $this->_userSec; }
		public function getDepartment() { return $this->_department; }
		public function getProcessName() { return $this->processName; }
		public function getProcessOrder() { return $this->processOrder; }
		public function getProcessDescription() { return $this->processDescription; }
		public function getStatus() { return $this->_status; }
	}