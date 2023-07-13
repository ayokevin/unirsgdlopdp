<?php
	class articleProcess {
		private $_db, $_log;
		private $_process, $_article;

		function __construct($_db, $_log, $processId, $articleId){
			$this->_db = $_db;
			$this->_log = $_log;
			if($processId != 0 && $articleId != 0){
				$this->setArticleProcess($processId, $articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->_process);
			unset($this->_article);
		}

		function setArticleProcess($processId, $articleId){
			$response = FALSE;
			$dataArticleProcess = $this->findArticleProcess($processId, $articleId);
			if($dataArticleProcess){
				$this->mapArticleProcess($dataArticleProcess);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticleProcess($processId, $articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article_process WHERE process_id = $processId AND article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("processId"=>$processId, "articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticleProcess($rs){
			$this->_process = new process($this->_db, $this->_log, $rs['process_id']);
			$this->_article = new article($this->_db, $this->_log, $rs['article_id']);
		}

		public function getProcess() { return $this->_process; }
		public function getArticle() { return $this->_article; }
	}