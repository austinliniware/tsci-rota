<?php
class getList {         #數據列表獲取類---基礎類

        public $tableName = "";  #獲取的表名稱
		public $exTableName = "";
        public $fieldList = "*";  #取列表時的字段
        public $fields = "*";  #取單條數據時的字段
        public $wheres = "";     #條件
		public $groupby = "";	 #group by
        public $orders = "";     #排序
        public $key = 'id';
        public $getNo = null;   #指定獲取第幾條的數據記錄,該屬性優先於getPageNo
        public $pageReNum = 20;   #指定每頁的記錄數量
		public $p =1;			#同getPageNo,指定獲取第幾頁的數據記錄,如果要使用該屬性，需將getNo置null

        protected $getPageNo = 1;   #指定獲取第幾頁的數據記錄,如果要使用該屬性，需將getNo置null
        public $recordCount = 0; #返回總共有多少條數據記錄
        public $pageCount = 0;   #返回總共有多少頁
        protected $isgetRC = true;  #是否計算條數

        protected $countSql = "";   #用於計算記錄總條數的SQL語句
        protected $eventFuncName = "";//處理每行數據的函數名

        public $debug = false;//調試狀態
		public $querySql='';
		public $select=''; #SQL語句，where之前的SQL語句，用於LEFT JOIN
		public $errMsg = '';
		
		public $permCheck = true;

        function __construct(){

        }
        
        public function getTableField($blob=false){
        	global $webdb;
        	$result=$webdb->getTableField($this->tableName);
        	if(!$blob){
        		foreach($result as $k => $v){
        			if($v['type']=='blob') $result[$k]['type']='string';
        			if($v['type']=='real') $result[$k]['type']='string';
        			if($v['type']=='datetime') $result[$k]['type']='string';
        		}
        	}
        	return $result;
        }

        public function setOrder($order){
        		$this->orders = $order;
        }

        public function setWhere($where){
        	if($where)
		        if($this->wheres) $this->wheres.=" and ".$where." ";
		        	else $this->wheres=$where;
        }
        
        public function setLimit($s,$l){
        	$this->getNo=$s;
        	$this->pageReNum=$l;
        }
        
        public function setField($field,$add=true){
        	if(is_array($field)) $fieldStr=implode(',',$field);
        	else $fieldStr=$field;
        	if($this->fieldList && $add) $this->fieldList.=','.$fieldStr;
        	else $this->fieldList=$fieldStr;
        	if($this->fields && $add) $this->fields.=','.$fieldStr;
        	else $this->fields=$fieldStr;
        }
        
        /**
         * 初始化完相關參數後，獲取數據
         *
         */
        public function getArray(){
                global $webdb;
                
                if($this->tableName && $this->permCheck && !permission::check($this->tableName,'s_tag')){
                	permission::errMsg();
                	return false;
                }

                if ($this->fieldList == "") {
                        $this->fieldList = "*";
                }
                if ($this->wheres <> ""){
                        $this->wheres = " where ".$this->wheres;
                }
                if ($this->groupby <> ""){
                        $this->groupby = " group by ".$this->groupby;
                }
                if ($this->orders <> ""){
                        $this->orders = " order by ".$this->orders;
                }
                if($this->exTableName){
                		$this->tableName.=','.$this->exTableName;
                }

                if ($this->isgetRC)
                {
                        if ($this->recordCount <= 0){
                        	if($this->select){
                        		$countSql=substr($this->select,strpos($this->select,"from"));
                        		if($this->wheres)$countSql.=" ".$this->wheres;
                        		$sql="select count(".($this->groupby?"DISTINCT(".str_replace('group by ','',$this->groupby).")":'*').") as 'rno' ".$countSql;
                        		$webdb->query($sql);
                                $webdb->next();
                        	}elseif ($this->countSql == ""){
                            	if($this->groupby){
                                    $sql="select count(DISTINCT(".str_replace('group by ','',$this->groupby).")) as 'rno' from ".$this->tableName." ".$this->wheres;
                                    $webdb->query($sql);
                                    $webdb->next();
                            	}else{
                                    $sql="select count(*) as 'rno' from ".$this->tableName." ".$this->wheres;
                                    $webdb->query($sql);
                                    $webdb->next();
                            	}
                            }else{
                                    $webdb->query($this->countSql);
                                    $webdb->next();
                            }
                            $this->recordCount=$webdb->f("rno");
                        }
                    $this->pageCount=ceil($this->recordCount / $this->pageReNum);
            	}
            	if($this->getNo===null){
					if($this->p) $this->getPageNo=$this->p;
	                $firstno=$this->pageReNum * ($this->getPageNo-1);
	                if($firstno<0) $firstno=0;
        		}else $firstno=$this->getNo;
        		if($this->select){
        			$sql=$this->select." ".$this->wheres." ".$this->groupby." ".$this->orders." limit "
                .$firstno.",".$this->pageReNum;
        		}else{
                	$sql="select ".$this->fieldList." from ".$this->tableName." ".$this->wheres." ".$this->groupby." ".$this->orders." limit "
                .$firstno.",".$this->pageReNum;
        		}
                if ($this->debug || $_GET['debug']) echo $sql;
				$this->querySql=$sql;
				//echo $sql;
                $result = $webdb->getList($sql);
                if ($this->eventFuncName){
                        if (is_array($result)){
                                foreach ($result as $k => $v){
                                        $result[$k] = call_user_func($this->eventFuncName,$v);
                                }
                        }
                }
                if(!$result) $result=array();
                return $result;
        }
        
        public function getRowArray($id,$field=''){
                global $webdb;
				
                if ($this->fields == "") {
                        $this->fields = "*";
                }
                $sql = 'select '.$this->fields.' from '.$this->tableName.' where '.$this->key.'='.string::qs($id);
                if ($this->wheres){
                        $sql .= ' and '.$this->wheres;
                }

                $webdb->query($sql);
                if ($webdb->next()){
                        $result = $webdb->Record;
                        if(!$field) return $result;
                        	else return $result[$field];
                }else{
                        return false;
                }
        }
        
		function getInfo($id,$field=null){
			if(!$id) return false;
			global $webdb;
            //spark s_tag=>e_tag
			if($this->tableName && $this->permCheck && !permission::check($this->tableName,'e_tag')){
            	permission::errMsg();
            	return false;
            }

			!$field && $field='*';
			$sql="select ".$field." from ".$this->tableName." where ".$this->key."='".$id."';";
			$result=$webdb->getValue($sql);
            if ($this->eventFuncName){
                $result = call_user_func($this->eventFuncName,$result);
            }
            if($field!='*') return $result[$field];
			else return $result;
		}
		function addData($array){
			global $webdb;
            unset($array[$this->key]);
//foreach($array as $k=>$v){
//	$sql="ALTER TABLE ".$this->tableName." ADD ".$k." varchar(130) NULL DEFAULT null;";
//	$webdb->query($sql);
//}
            if($this->tableName && $this->permCheck && !permission::check($this->tableName,'a_tag')){
            	permission::errMsg();
            	return false;
            }

			$field_ary=$this->getTableField();
			foreach($field_ary as $field){
				$fields[]=$field['name'];
			}
			foreach($array as $k=>$v){
				if(!in_array($k,$fields)) unset($array[$k]);
			}
			return $webdb->insert($array,$this->tableName);
		}
		function editData($array,$id){
			global $webdb;
            unset($array[$this->key]);
			if($this->tableName && $this->permCheck && !permission::check($this->tableName,'e_tag')){
            	permission::errMsg();
            	return false;
            }

			$field_ary=$this->getTableField();
			foreach($field_ary as $field){
				$fields[]=$field['name'];
			}
			foreach($array as $k=>$v){
				if(!in_array($k,$fields)) unset($array[$k]);
			}
			return $webdb->update($array,$this->tableName,"where ".$this->key."='".$id."'");
		}
		function delete($id){
			global $webdb;
            //print_r($this->permCheck && !permission::check($this->tableName,'d_tag'));exit;
			if($this->tableName && $this->permCheck && !permission::check($this->tableName,'d_tag')){
            	permission::errMsg();
            	return false;
            }
			return $webdb->query("delete from ".$this->tableName." where ".$this->key."='".$id."'");
		}
		
        public function getPageInfoHTML($page = 0,$url=''){
               	if(!$url){
               		$html = true;
               		$url='?'.$this->urlkill('p',false).'&p=';
               	}
                if ($page){
                	if($page=1) $result = '共有<strong>'.$this->recordCount.'</strong>條記錄，分<strong>'.$this->pageCount.'</strong>頁顯示';
						else $result = '當前第 <input name="goPageNo" value="'.$this->getPageNo.'" size="2"><input type="button" value="GO" onclick="window.location.href=\''.$url.'\'+$(\'goPageNo\').value"> 頁';
                }else{
                	$htmlstr='%pagestr%';
                	$fstr='<a href="%url%" class="BtnFirst">首頁</a>';
                	$pstr='<a href="%url%" class="BtnPrev">上一頁</a>';
                	$nstr='<a href="%url%" class="BtnNext">下一頁</a>';
                	$estr='<a href="%url%" class="BtnEnd">尾頁</a>';
                	$goto='<a href="%url%" class="BtnNum">%num%</a>';
                	$now='<em class="BtnNumSelect">%num%</em>';
                	
                	if ($this->getPageNo>1){
                		$fstr=str_replace('%url%',$url.'1',$fstr);
                		$pstr=str_replace('%url%',$url.($this->getPageNo-1),$pstr);
                	}else{
                		$fstr=str_replace('%url%','javascript:;',$fstr);
                		$pstr=str_replace('%url%','javascript:;',$pstr);
                	}
                	
                	if ($this->getPageNo != $this->pageCount && $this->pageCount > 0){
                		$nstr=str_replace('%url%',$url.($this->getPageNo+1),$nstr);
                		$estr=str_replace('%url%',$url.($this->pageCount),$estr);
                	}else{
                		$nstr=str_replace('%url%','javascript:;',$nstr);
                		$estr=str_replace('%url%','javascript:;',$estr);
                	}
                	
                	$begin=(($this->getPageNo-4)>0)?$this->getPageNo-4:0;
                	$end=(($this->getPageNo+3)<$this->pageCount)?$this->getPageNo+3:$this->pageCount;
                	$numstr='';
                	for($i=$begin;$i<$end;$i++){
                		if($this->getPageNo==$i+1){
                			$tstr=str_replace('%num%',($i+1),$now);
                		}else{
                			$tstr=str_replace('%url%',$url.($i+1),$goto);
                			$tstr=str_replace('%num%',($i+1),$tstr);
                		}
                		$numstr.=$tstr;
                	}
                	
                	$pagestr=$fstr.$pstr.$numstr.$nstr.$estr;
                	
                	$pagehtml=str_replace('%pagestr%',$pagestr,$htmlstr);
                }
                return $pagehtml;
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
			
				for($from;$from<=$to;$from++){
					if($curpage==$from){
						$pagehtml.=' <li><a href="#">'.$from.'</a></li>';
					}else{
						$pagehtml.=' <li><a href="'.$url.($from).'" '.$htmlClass.'>'.($from).'</a></li>';
					}
					
				}
				$pagehtml = ' <li><a href="'.$url.'1">&lt;&lt;</a></li>'.$pagehtml;
				$pagehtml.=' <li><a href="'.$url.$totalPage.'">&gt;&gt;</a></li>';
				return $pagehtml;
				} else return;
        }
        
        function urlkill($key,$fullurl=true){        //多個key可以通過|分隔
                 $url=preg_replace('/&('.$key.')\=[^&]*/','', '&' . $_SERVER['QUERY_STRING']);
                 if($fullurl) $url = $_SERVER['SCRIPT_NAME'] . '?' .substr($url,1);
                         else $url=substr($url . $ext,1);
                 $url=str_replace("&amp;","[AND]",$url);
                 $url=str_replace("&","[AND]",$url);
                 $url=str_replace("[AND]","&amp;",$url);
                 return $url;
        }
        /*
         * 為了兼容子類
         */
        function getList(){
        	return $this->getArray();
        }
        function add($array){
        	return $this->addData($array);
        }
        function edit($array,$id){
        	return $this->editData($array,$id);
        }
        function del($id){
        	return $this->delete($id);
        }
        function setKw($ary){
        	return false;
        }
        /*
         * 為了加載類
         */
		function none(){}
}
?>
