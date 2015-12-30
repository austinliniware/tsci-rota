<?php
class hospital extends getList {
	var $errMsg='';
	public function __construct(){
		$this->tableName = '_web_hospital';
		$this->key = 'id';
		$this->wheres = '1';
		$this->orders = 'id';
		$this->pageReNum = 15;
	}
	public function getProductPageInfoHTML($page = 0,$url=''){
		if(!$url){
			$html = true;
			$url='?'.$this->urlkill('p',false).'&amp;p=';
		}

		$totalPage=$this->pageCount;
		$curpage=$this->getPageNo;
		if ($totalPage>1) {
			if($page > $totalPage) {
				$from = 1;
				$to = $totalPage;
			} else {
				$from = $curpage - intval($page/2);
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} elseif($to > $totalPage) {
					$from = $totalPage - $page + 1;
					$to = $totalPage;
				}
			}

			// $rpagehtml='<div class="pagingDataBox"> First<b>'.((($curpage-1)*$this->pageReNum)+1).'</b>-<b>'.($curpage==$to?$this->recordCount:(($curpage)*$this->pageReNum)).'</b>data, a total of<b>'.$this->recordCount.'</b>data (points<b>'.$this->pageReNum.'</b>Show)</div>';
			
			for($from;$from<=$to;$from++){
				if($curpage==$from){
					$pagehtml.='<li class="current"><a title="第'.$from.'頁">'.$from.'</a></li>';
				}else{
					$pagehtml.='<li class=""><a href="'.$url.($from).'" title="第'.$from.'頁" >'.($from).'</a></li>';
				}
			}

			$prev_page= ($curpage>1) ? ($curpage-1) : 1;
			$next_page = ($curpage<$totalPage) ? $curpage+1 : $curpage;
			
			$rpagehtml .= '<ul><li class="pagBtn upperestBtn"><a href="'.$url.(1).'">最前頁</a></li><li class="pagBtn previousBtn"><a href="'.$url.($prev_page).'" title="回上一頁">Previous</a></li>'.$pagehtml;
			$rpagehtml .= '<li class="pagBtn nextBtn"><a href="'.$url.($next_page).'" title="到下一頁">Next</a></li><li class="pagBtn lastPageBtn"><a href="'.$url.($totalPage).'">最後一頁</a></li>';
			$rpagehtml .= '</ul>';
			return $rpagehtml;
		} else return;
	}
}
?>