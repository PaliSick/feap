<?
class Paginado {
	/*Config Vars*/
	var $rowsPerPage = 30;
	var $showDots = false;
	var $showNextPrev = false;
	var $get_var = 'page';
	var $maxPagesShow = 4;

	/*Internal Vars*/
	var $oriSql = '';
	var $newSql = '';
	var $actualPage = 1;
	var $html = '';
	var $totalRows = 0;
	var $totalPages = 0;

	function Paginado($sql, &$db, $page, $limit, $linkPattern, $linkPage1) {
		$this->db = $db;

		$this->linkPattern = str_replace('--1', '%d', $linkPattern);
		$this->linkPage1 = (empty($linkPage1)) ? $this->linkPattern : $linkPage1 ;
		$this->actualPage = $page;
		$this->rowsPerPage = $limit;


		$_pagi_sql = str_replace(';', '', $sql); //y los ;
		//$_pagi_sql = preg_replace('/LIMIT ([0-9]* ?,)? ?[0-9]*;?/', '', $_pagi_sql); //le borramos el limite que tenga
		$this->oriSql = $_pagi_sql;

		$this->get_total_pages();
		$this->actualPage = (empty($page)) ? 1 : $page;
	}
	function set_page() {
        //$this->actualPage = (isset($_GET[$this->get_var]) && $_GET[$this->get_var] != "") ? $_GET[$this->get_var] : 1;
        return $this->actualPage;
    }
	function get_total_rows() {
		$q = $this->db->query($this->oriSql);
		$this->totalRows = mysql_num_rows($q);
		return $this->totalRows;
	}
	function get_total_pages() {
		$this->totalPages = ceil($this->get_total_rows() / $this->rowsPerPage);
		return $this->totalPages;
	}
	function limitSql() {
		if ($this->get_total_rows() >= $this->rowsPerPage) {
			$start = ($this->set_page() - 1) * $this->rowsPerPage;
			$tmp_sql = str_replace('%', '%%', $this->oriSql); //los % los hacemos literales para q no se bardee con los %LIKE%
			$this->newSql = sprintf('%s LIMIT %s, %s', $tmp_sql, $start, $this->rowsPerPage);
		} else {
			$this->newSql = $this->oriSql;
		}

		return $this->newSql;
	}

	function buildPaginado() {

		if ($this->totalPages < 2)
		{
			return '<div id="paginado" class="clearfix"><ul class="clearfix"><li class="current">1</li></ul></div>';
		}


		$start 	= ($this->actualPage - 1 - (ceil($this->maxPagesShow / 2)) < 0) ? 0 : $this->actualPage - 1 - (ceil($this->maxPagesShow / 2));
		$end 	= ($this->totalPages < $this->maxPagesShow) ? $this->totalPages :
						($this->actualPage + (ceil($this->maxPagesShow / 2)) > $this->totalPages) ? $this->totalPages : $this->actualPage + (ceil($this->maxPagesShow / 2));

/*
		$start = (($this->actualPage - 1) * $this->rowsPerPage);
		$end = $this->actualPage * $this->rowsPerPage;
*/

		$this->html = '<div id="paginado" class="clearfix"><ul class="clearfix">';

		if ($this->showNextPrev)
			if ( ($this->actualPage - 1) > 0) {
					if (($this->linkPage1 != $this->linkPattern) && ($this->actualPage-1 == 1)) $this->html.= '<li class="prev-next"><a href="'.$this->linkPage1.'">&lt; Anterior</a></li>';
					else
					$this->html.= '<li class="prev-next"><a href="'.sprintf($this->linkPattern, $this->actualPage - 1).'">&lt; Anterior</a></li>';
			} else	$this->html.= '<li class="prev-next-dis">&lt; Anterior</li>';

		if ($this->showDots && ($start >= 1)) $this->html.= '<li class="dots">...</li>';

		for ($i = $start; $i < $end; $i++) {

			if ($i == $this->actualPage-1) {
				$this->html.= '<li class="current '.(($i+1 < $end) ? '' : 'last').'">'. ($i + 1) .'</li>';
			} else {
				if (($this->linkPage1 != $this->linkPattern) && ($i + 1 == 1)) $this->html.= '<li><a href="'.$this->linkPage1.'">'. ($i + 1) .'</a></li>';
				else if($i+1 < $end) $this->html.= '<li><a href="'.sprintf($this->linkPattern, $i + 1).'">'. ($i + 1) .'</a></li>';
				else $this->html.= '<li class="last"><a href="'.sprintf($this->linkPattern, $i + 1).'">'. ($i + 1) .'</a></li>';
			}
		}

		if ($this->showDots && ($end < $this->totalPages)) $this->html.= '<li class="dots">...</li>';

		if ($this->showNextPrev) {
			if ( ($this->actualPage + 1) <= $this->totalPages) {
					$this->html.= '<li class="prev-next"><a href="'.sprintf($this->linkPattern, $this->actualPage + 1).'">Siguiente &gt;</a></li>';
			} else 	$this->html.= '<li class="prev-next-dis">Siguiente &gt;</li>';
		}
		$this->html.= '</ul></div>';


		return $this->html;
	}
	function renderPage() {
		$query = $this->db->query($this->newSql);
		$result = array();
		while ($row = mysql_fetch_assoc($query))
		{
			$result[] = callback($row, $this->db);
		}

		return $result;
	}
	function pageInfo() {
		$result = array(
			'start'	=> ($this->set_page()-1) * $this->rowsPerPage,
			'end'	=> ($this->totalRows >=  $this->set_page()*$this->rowsPerPage) ? $this->set_page()*$this->rowsPerPage : $this->totalRows,
			'total'	=> $this->totalRows
		);
		return $result;
	}

	function connectDb($server, $user, $pass, $db) {
		$connect = mysql_connect($server, $user, $pass);
		mysql_select_db($db, $connect);
		return $connect;
	}
}