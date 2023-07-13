<?php
	class option {
		private $_db, $_log;
		private $optionsId, $optionType, $fatherOption, $optionName, $optionComponent, $optionOrder;

		function __construct($_db, $_log, $optionId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($optionId != 0){
				$this->setOption($optionId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->optionsId);
			unset($this->optionType);
			unset($this->fatherOption);
			unset($this->optionName);
			unset($this->optionComponent);
			unset($this->optionOrder);
		}

		function setOption($optionId){
			$response = FALSE;
			$dataOption = $this->findOption($optionId);
			if($dataOption){
				$this->mapOption($dataOption);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("optionId"=>$optionId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findOption($optionId){
			$response = false;
			$sql =  "SELECT * FROM security.option WHERE options_id = $optionId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("optionId"=>$optionId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("optionId"=>$optionId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapOption($rs){
			$this->optionsId = $rs['options_id'];
			$this->optionType = $rs['option_type'];
			$this->fatherOption = $rs['father_option'];
			$this->optionName = $rs['option_name'];
			$this->optionComponent = $rs['option_component'];
			$this->optionOrder = $rs['option_order'];
		}


		public function getOptionList($userId, $fatherId){
			$response = false;
			$sql =  "select distinct (o.options_id),o.father_option,o.option_order,o.option_name,o.option_component,o.option_type ".  
			"from security.user_sec us, security.rol_user_sec rus, security.rol_option ro, security.option o ".
			"where us.user_sec_id = rus.user_sec_id ".
			"and rus.rol_id = ro.rol_id ".
			"and ro.options_id =o.options_id ".
			"and rus.rol_id in (select rus2.rol_id ".
			"from security.rol_user_sec rus2 ".
			"where rus2.user_sec_id  = $userId) ".
			"and ro.status_id = 13 ".
			"and rus.status_id = 19 ".
			"and o.father_option  = $fatherId ".
			"order by 2,3 asc;";
					
			$rs = $this->_db->query($sql);
			if($rs) {
				$response = $rs;
				$arrLog = array("input"=>array(	"userId"=>$userId,
												"fatherId"=>$fatherId),
								"output"=>$response,
								"sql"=>$sql);
								
				$this->_log->debug(__FUNCTION__,$arrLog);
			} else {
				$arrLog = array("input"=>array(	"userId"=>$userId,
												"fatherId"=>$fatherId),
								"sql"=>$sql,
								"error"=>$this->_db->getLastError());
				$this->_log->error(__FUNCTION__,$arrLog);  
			}

			return $response;
		}
		
		public function getOptionsId() { return $this->optionsId; }
		public function getOptionType() { return $this->optionType; }
		public function getFatherOption() { return $this->fatherOption; }
		public function getOptionName() { return $this->optionName; }
		public function getOptionComponent() { return $this->optionComponent; }
		public function getOptionOrder() { return $this->optionOrder; }
	}