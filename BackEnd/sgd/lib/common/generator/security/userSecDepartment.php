<?php
	class userSecDepartment {
		private $_db, $_log;
		private $userSecDepartmentId, $_department, $_userSec, $_status;

		function __construct($_db, $_log, $userSecDepartmentId, $departmentId, $userSecId, $statusId){
			$this->_db = $_db;
			$this->_log = $_log;
			if($userSecDepartmentId != 0 && $departmentId != 0 && $userSecId != 0 && $statusId != 0){
				$this->setUserSecDepartment($userSecDepartmentId, $departmentId, $userSecId, $statusId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->userSecDepartmentId);
			unset($this->_department);
			unset($this->_userSec);
			unset($this->_status);
		}

		function setUserSecDepartment($userSecDepartmentId, $departmentId, $userSecId, $statusId){
			$response = FALSE;
			$dataUserSecDepartment = $this->findUserSecDepartment($userSecDepartmentId, $departmentId, $userSecId, $statusId);
			if($dataUserSecDepartment){
				$this->mapUserSecDepartment($dataUserSecDepartment);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("userSecDepartmentId"=>$userSecDepartmentId, "departmentId"=>$departmentId, "userSecId"=>$userSecId, "statusId"=>$statusId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findUserSecDepartment($userSecDepartmentId, $departmentId, $userSecId, $statusId){
			$response = false;
			$sql =  "SELECT * FROM security.user_sec_department WHERE user_sec_department_id = $userSecDepartmentId AND department_id = $departmentId AND user_sec_id = $userSecId AND status_id = $statusId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("userSecDepartmentId"=>$userSecDepartmentId, "departmentId"=>$departmentId, "userSecId"=>$userSecId, "statusId"=>$statusId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("userSecDepartmentId"=>$userSecDepartmentId, "departmentId"=>$departmentId, "userSecId"=>$userSecId, "statusId"=>$statusId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapUserSecDepartment($rs){
			$this->userSecDepartmentId = $rs['user_sec_department_id'];
			$this->_department = new department($this->_db, $this->_log, $rs['department_id']);
			$this->_userSec = new userSec($this->_db, $this->_log, $rs['user_sec_id']);
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
		}

		public function getUserSecDepartmentId() { return $this->userSecDepartmentId; }
		public function getDepartment() { return $this->_department; }
		public function getUserSec() { return $this->_userSec; }
		public function getStatus() { return $this->_status; }
	}