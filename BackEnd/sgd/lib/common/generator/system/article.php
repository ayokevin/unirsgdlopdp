<?php
	class article {
		private $_db, $_log;
		private $articleId, $_project, $articleName, $articleDescription, $articleFatherId, $_articleApp, $articleObservation, $_status, $articleActualState, $articleJustification, $_articleComplian, $articleUpdateAt;

		function __construct($_db, $_log, $articleId = 0){
			$this->_db = $_db;
			$this->_log = $_log;
			if($articleId != 0){
				$this->setArticle($articleId);
			}
		}

		function __destruct(){
			unset($this->_db);
			unset($this->_log);
			unset($this->articleId);
			unset($this->_project);
			unset($this->articleName);
			unset($this->articleDescription);
			unset($this->articleFatherId);
			unset($this->_articleApp);
			unset($this->articleObservation);
			unset($this->_status);
			unset($this->articleActualState);
			unset($this->articleJustification);
			unset($this->_articleComplian);
			unset($this->articleUpdateAt);
		}

		function setArticle($articleId){
			$response = FALSE;
			$dataArticle = $this->findArticle($articleId);
			if($dataArticle){
				$this->mapArticle($dataArticle);
				$response = TRUE;
			}
			$arrLog = array("input"=>array("articleId"=>$articleId), "output"=>$response);
			$this->_log->warning(__METHOD__,$arrLog);
			return $response;
		}

		function findArticle($articleId){
			$response = false;
			$sql =  "SELECT * FROM system.article WHERE article_id = $articleId";

			$rs = $this->_db->query($sql);
			if($rs){
				$response = $rs[0];
			}
			else{
				$arrLog = array("input"=>array("articleId"=>$articleId), "sql"=>$sql, "error"=>$this->_db->getLastError());
				$this->_log->error(__METHOD__,$arrLog);
			}
			$arrLog = array("input"=>array("articleId"=>$articleId), "output"=>$response, "sql"=>$sql);
			$this->_log->debug(__METHOD__,$arrLog);
			return $response;
		}

		function mapArticle($rs){
			$this->articleId = $rs['article_id'];
			$this->_project = new project($this->_db, $this->_log, $rs['project_id']);
			$this->articleName = $rs['article_name'];
			$this->articleDescription = $rs['article_description'];
			$this->articleFatherId = $rs['article_father_id'];
			$this->_articleApp = new articleApp($this->_db, $this->_log, $rs['article_apply']);
			$this->articleObservation = $rs['article_observation'];
			$this->_status = new status($this->_db, $this->_log, $rs['status_id']);
			$this->articleActualState = $rs['article_actual_state'];
			$this->articleJustification = $rs['article_justification'];
			$this->_articleComplian = new articleComplian($this->_db, $this->_log, $rs['article_compliance']);
			$this->articleUpdateAt = $rs['article_update_at'];
		}

		public function getArticleId() { return $this->articleId; }
		public function getProject() { return $this->_project; }
		public function getArticleName() { return $this->articleName; }
		public function getArticleDescription() { return $this->articleDescription; }
		public function getArticleFatherId() { return $this->articleFatherId; }
		public function getArticleApp() { return $this->_articleApp; }
		public function getArticleObservation() { return $this->articleObservation; }
		public function getStatus() { return $this->_status; }
		public function getArticleActualState() { return $this->articleActualState; }
		public function getArticleJustification() { return $this->articleJustification; }
		public function getArticleComplian() { return $this->_articleComplian; }
		public function getArticleUpdateAt() { return $this->articleUpdateAt; }
	}