<?php
require_once ('C://xampp/htdocs/sgd/lib/common/reference/reference.php');
require_once ('C://xampp/htdocs/sgd/lib/common/client/client.php');
	class department {
		private $_db, $_log;
		private $departmentId, $_client, $departmentName, $fatherId, $_statusId;

		function __construct($_db, $_log, $departmentId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($departmentId != 0){
				$this->setDepartment($departmentId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->departmentId);
			unset($this->_client);
			unset($this->departmentName);
			unset($this->fatherId);
			unset($this->_statusId);
		}

		function setDepartment($departmentId){
			$response = FALSE;
			$dataDepartment = $this->findDepartment($departmentId);
			if($dataDepartment){
				$this->mapDepartment($dataDepartment);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("departmentId"=>$departmentId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findDepartment($departmentId){
			$response = false;
			$sql =  "SELECT * FROM common.department WHERE department_id = $departmentId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("departmentId"=>$departmentId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("departmentId"=>$departmentId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapDepartment($rs){
			$this->departmentId = $rs['department_id'];
			$this->_client = new client($this->_db, $this->_log, $rs['client_id']);
			$this->departmentName = $rs['department_name'];
			$this->fatherId = $rs['father_id'];
			$this->_statusId = new reference($this->_db, $this->_log, $rs['department_status_id']);
		}

		public function insertDepartment($client_id,$department_name,$father_id,$department_status_id){
			$response = false;
			$sql =  "INSERT INTO common.department ".
			"(client_id, department_name, father_id, department_status_id) ".
			"VALUES($client_id, '$department_name', $father_id, $department_status_id) returning department_id";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setDepartment($rs[0]['department_id']):true;
				$arrLog = array("input"=>array( "client_id"=>$client_id,
												"department_name",$department_name,
												"father_id",$father_id,
												"department_status_id",$department_status_id),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "client_id"=>$client_id,
												"department_name",$department_name,
												"father_id",$father_id,
												"department_status_id",$department_status_id),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function updateDepartment($department_id,$department_name,$father_id,$department_status_id){
			$response = false;
			$sql =  "UPDATE common.department ".
			"SET department_name='$department_name', father_id=$father_id, department_status_id=$department_status_id ".
			"WHERE department_id=$department_id returning department_id;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = (is_array($rs))?$this->setDepartment($rs[0]['department_id']):true;
				$arrLog = array("input"=>array( "department_id"=>$department_id,
												"department_name",$department_name,
												"father_id",$father_id,
												"department_status_id",$department_status_id),
								"output"=>$response,
								"sql"=>$sql);
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array( "department_id"=>$department_id,
												"department_name",$department_name,
												"father_id",$father_id,
												"department_status_id",$department_status_id),
				"sql"=>$sql,
				"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}

		public function listDepartment($client_id){
			$response = false;
			if($client_id==='')
			{
				$sql =  "SELECT d.department_id,d.client_id,c.client_name, d.department_name, d.father_id, d.department_status_id,r.reference_name ".
				"FROM common.department d ,common.reference r,common.client c ". 
				"where d.department_status_id = r.reference_id and d.client_id = c.client_id ".
				"order by 1 asc;";
			}
			else{
				$sql =  "SELECT d.department_id,d.client_id,c.client_name, d.department_name, d.father_id, d.department_status_id,r.reference_name ".
				"FROM common.department d ,common.reference r,common.client c ". 
				"where d.department_status_id = r.reference_id and d.client_id = c.client_id ".
				"and d.client_id = '$client_id' order by 1 asc;";
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

		

		public function getDepartmentId() { return $this->departmentId; }
		public function getClient() { return $this->_client; }
		public function getDepartmentName() { return $this->departmentName; }
		public function getFatherId() { return $this->fatherId; }
		public function getDepartmentStatus() { return $this->_statusId; }
	}