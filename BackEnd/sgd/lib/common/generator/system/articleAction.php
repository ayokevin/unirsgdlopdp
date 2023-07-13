<?php
	class articleAction {
		private $_db, $_log;
		private $_action, $_article;

		function __construct($_db, $_log, $actionId, $articleId){
			$this->_db = $_db;
			$this->_log = $_log;
			if($actionId != 0 && $articleId != 0){
				$this->setArticleAction($actionId, $articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->_action);
			unset($this->_article);
		}

		function setArticleAction($actionId, $articleId){
			$response = FALSE;
			$dataArticleAction = $this->findArticleAction($actionId, $articleId);
			if($dataArticleAction){
				$this->mapArticleAction($dataArticleAction);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticleAction($actionId, $articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article_action WHERE action_id = $actionId AND article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("actionId"=>$actionId, "articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticleAction($rs){
			$this->_action = new action($this->_db, $this->_log, $rs['action_id']);
			$this->_article = new article($this->_db, $this->_log, $rs['article_id']);
		}

		public function getAction() { return $this->_action; }
		public function getArticle() { return $this->_article; }
	}