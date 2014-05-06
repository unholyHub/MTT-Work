<?php
/*
 * 
 */
include_once dirname(dirname(__DIR__)).'/config/env.php';
class Database {
    protected $table;
    protected $object_of;

    function __construct() {}

    function connect(){
        @$db=mysql_connect(Env::$DB['host'], Env::$DB['username'], Env::$DB['password']) or die ('MYSQL ERROR: ' . mysql_error());
        @mysql_select_db(Env::$DB['database']);
        @mysql_query("SET NAMES ".Env::$DB['encoding'],$db);
        @mysql_query("SET CHARACTER SET ".Env::$DB['encoding']);
        return @$db;
    }

    function query(&$result,$query){
        //  $statprom=microtime(TRUE);
        @$result=mysql_query($query);
        $num=@mysql_num_rows($result);
        //echo (microtime(TRUE)-$statprom)." <small>".$query."</small><br style='clear:both;'>";
        //echo $query."<br>";
        return $num;
    }

    function insert_query($query){
        //  $statprom=microtime(TRUE);
        mysql_query($query);
        //echo (microtime(TRUE)-$statprom)." <small>".$query."</small><br style='clear:both;'>";
        //echo $query."<br>";
        return mysql_insert_id();
    }

    function fetchRow($result){
        return mysql_fetch_row($result);
    }

    function fetchObject($result){
        return mysql_fetch_object($result);
    }

    /*
     * Function Find($params)
     * $params is an array and the element keys can be:
     * all, id, where, oreder, limit
     * Examples:
     *      object.find(id=>$some_id);
     *      ->returns an object
     *      object.find(all=>1)
     *      ->returns an array of objects
     *      object.find(all=>1,where=>$conditions,order=>$order_by,limit=>$limit)
     *      ->returns an array of object filtered by $conditions, sorted by $order_by and limited by $limit
     */
    public function find(array $params){
        $query='SELECT * FROM `'.$this->table.'`';
        if(isset($params['id'])) $query.=' WHERE id='.$params['id'];
        elseif(isset($params['all'])){
            if(isset($params['where'])) $query.=' WHERE '.$params['where'];
            if(isset($params['order'])) $query.=' ORDER BY '.$params['order'];
            if(isset($params['limit'])) $query.=' LIMIT '.$params['limit'];
        }
        $this->Query($result, $query); //print '<br />'.$query;
        if(isset($params['id'])){
            return mysql_fetch_object($result, $this->object_of);
        }elseif(isset($params['all'])){
            $objects=array();
            while($object=mysql_fetch_object($result, $this->object_of)){
                $objects[]=$object;
            }
            return $objects;
        }else{
            return null;
        }
    }

    public function paginate(array $params, &$links){
        $query='SELECT * FROM `'.$this->table.'`';
        if(isset($params['id'])) $query.=' WHERE id='.$params['id'];
        elseif(isset($params['all'])){
            if(isset($params['where'])) $query.=' WHERE '.$params['where'];
            if(isset($params['order'])) $query.=' ORDER BY '.$params['order'];
            //if(isset($params['limit'])) $query.=' LIMIT '.$params['limit'];
        }
        $links=$this->separateInPages($query,$params['page_size']);
        $params['limit']=$this->genLimit($params['page_size']);
        return $this->find($params);
    }

    protected function separateInPages ($query,$page_size){

        $size=$this->query($q, $query);

        if($size>0){
            $links='<div class="pages">';
            $count=ceil($size/$page_size);
            //$links='';
            if (isset($_GET['pg'])) $page = $_GET['pg']; else $page = 1;

            $argum='';
            if($_GET)
            foreach($_GET as $key=>$val){
                if($key!='pg'){
                    $argum.='&'.$key.'='.$val;
                }
            }
            if($page>1) $links.='<a href="'.$_SERVER[PHP_SELF].'?pg=1'.$argum.'"><<</a> <a href="'.$_SERVER[PHP_SELF].'?pg='.($page-1).$argum.'">Предишна</a>';

            for($i=($page-4);$i<=$count&&$i<=$page+5;$i++){
                if($i>0){
                    if($i!=$page) $links.=' <a href="'.$_SERVER[PHP_SELF].'?pg='.$i.$argum.'">'.$i.'</a> ';
                    else $links.=' <b>'.$i.'</b>';
                }
            }

            $links.=' './*$page.*/' от '.$count.' ';
            if($page<$count) $links.='<a href="'.$_SERVER[PHP_SELF].'?pg='.($page+1).$argum.'">Следваща</a> <a href="'.$_SERVER[PHP_SELF].'?pg='.$count.$argum.'">>></a>';
            $links.='</div>';
            return '<br clear="all" />'.$links;
        }
        return '';
    }

    protected function genLimit($page_size){
        if (isset($_GET['pg'])) $page = $_GET['pg']; else $page = 1;
        $from = (($page * $page_size) - $page_size);
        return $from.','.$page_size;
    }
}
?>
