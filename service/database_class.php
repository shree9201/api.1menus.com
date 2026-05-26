<?php
//////////////////////////////////////////////////////////////////////////////////////////////
// File Name        : database_class.php													//
// Craeted By       : Vishwajeet Mahadik													//
// Created Date     : 22-05-2022															//
// File Modified By : Vishwajeet Mahadik													//
// Modify  Date     : 22-05-2022															//
// Description      : Database main service class to process db values						//
//////////////////////////////////////////////////////////////////////////////////////////////

class database_class {
    
    var $post;
    var $get;
    var $request;
    var $dbclass;
    var $files;
    var $connection;
    public function __construct(){
        $this->post = $_POST;
        $this->get = $_GET;
        $this->request = $_REQUEST;
        $this->files = $_FILES;
        //$this->connection = null;
        
        // 1. Create a database connection
        if (! isset ( $connection )) {
            
            
            //$connection = @mysql_connect ( DB_SERVER, DB_USER, DB_PASS );
            //$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            $this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            //$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            //s$this->connection = $connection;
            //	mysql_set_charset('utf8',$connection);
            //if (! $connection) {
            //	die ( "<h1>server connection failed: " . mysql_error ()."</h1>" );
            //}
        }
        // 2. Select a database to use
        //if (! isset ( $db_select )) {
        //$db_select = mysql_select_db ( DB_NAME, $connection );
        //	$db_select = $connection->select_db(DB_NAME);
        
        //if (! $db_select) {
        //	die ( "<h1>Database selection failed: " . mysql_error ()."</h1>" );
        //}
        //}
        mysqli_query($this->connection,'SET character_set_results=utf8');
        //mysqli_query($this->connection,'SET names=utf8');
        mysqli_query($this->connection,'SET character_set_client=utf8');
        mysqli_query($this->connection,'SET character_set_connection=utf8');
        mysqli_query($this->connection,'SET character_set_results=utf8');
        mysqli_query($this->connection,'SET collation_connection=utf8_general_ci');
    }
    public function db_query($sql) {
        $last_qurey = $sql;
        return mysqli_query($this->connection,$sql);
    }
    
    public function fetch_all_records($res) {
        global $connection;
        return mysqli_fetch_object($res);
    }
    
    public function db_last_query() {
        return $last_qurey;
    }
    
    public function Display_all_records_from_query($sql)
    {
        global $connection;
        $result=mysqli_query($this->connection,$sql);
        return mysqli_fetch_object($result);
    }
    public function getNumberOfRecordsFromQuery($sql)
    {
        
        $result=mysqli_query($this->connection,$sql);
        
        $totalCount=0;
        for($j=0;$row=mysqli_fetch_object($result);$j++)
        {
            $totalCount++;
        }
        return  $totalCount;
        
    }
    
    public function sendEmailNotification($to , $from , $subject , $body){
        
        $header = "From: ". $from . " \r\n". "Content-type: text/html; charset=iso-8859-1"; //optional headerfields
        ini_set("SMTP",SMTP);
        ini_set("smtp_port",SMTPPORT);
        return $result = mail($to, $subject, $body, $header); //mail command :)
    }
    public function insert($array,$table){
        
        if(count($array)>0){
            $sql = "INSERT INTO ".$table." set ,";
            //while (list($key, $val) = each($array)) {
            foreach($array as $key => $val){
                
                
                
                
                //echo "$key => $val<br>";
                $sql.= ",".$key."='".addslashes($val)."' ";
            }
            $sql = str_replace('set ,,', 'set ', $sql);
            return $this->insertQuery($sql);
        }else{
            echo "Array should be greater than 0";
        }
    }
    
    public function insertQuery($sql=NULL){
        if($sql!="")
        {
            if(mysqli_query($this->connection,$sql))
            {
                //$results=mysqli_query($this->connection,$sql);
                $id = mysqli_insert_id($this->connection);
                if($id==0)
                {
                    $sql="SELECT LAST_INSERT_ID() as id";
                    $info = $this->select($sql);
                    if(isset($info[0])){
                        if(is_array($info[0]) && isset($info[0]['id'])){
                            $id = $info[0]['id'];
                        } else if(is_object($info[0]) && isset($info[0]->id)){
                            $id = $info[0]->id;
                        }
                    }
                }
                return $id;
            }
            else
            {
                $this->error($sql);return "Invalid  Query";
            }
        }
        else
            return "Invalid  Query";
    }
    
    public function displayArray($array){
        echo "<pre>";
        print_r($array);
    }
    
    public function select ($sql=NULL)
    {
        if($sql!="")
        {
            $count = 0;
            $data  = array();
            $results=mysqli_query($this->connection,$sql);
            if($results)
            {
                
                while ( $row = mysqli_fetch_object($results))
                {
                    $data[$count] = $row;
                    $count++;
                }
                return $data;
            }
            else
            {
                $this->error($sql);return "Invalid Select Query";
            }
        }
        else
            return "Invalid Select Query";
    }
    
    
    public function selectOneField($column=NULL, $sql=NULL){
        $records = $this->select($sql);
        $records=$records[0]->$column;
        echo $records;
    }
    public function selectOneFieldReturn($column=NULL, $sql=NULL){
        $records = $this->select($sql);
        $records=$records[0]->$column;
        return $records;
    }
    public function getRecordsCount($table,$where=null){
        $sql = "select count(*) as count from ".$table."";
        if($where!=null){
            $sql.=$where;
        }
        echo $this->selectCount($sql);
    }
    public function getRecordsCountReturn($table,$where=null){
        $sql = "select count(*) as count from ".$table."";
        if($where!=null){
            $sql.=" where ".$where;
        }
        return $this->selectCount($sql);
    }
    public function selectSingleRowData($table,$id){
        $sql = $this->select("select * from ".$table." where id=".$id);
        return $sql[0];
    }
    public function getMyRecordValue($table , $id , $key){
        $data = $this->select("select ".$key." as Myvalue from ".$table. " where id = ".$id);
        $data = $data[0]?$data[0]->Myvalue:'';
        return $data;
    }
    public function pageTrack(){
        $page 	= isset($_REQUEST['page'])?$_REQUEST['page']:"index";
        $data 	= isset($_REQUEST['data'])?$_REQUEST['data']:"";
        $key 	= isset($_REQUEST['key'])?$_REQUEST['key']:"";
        $value 	= isset($_REQUEST['value'])?$_REQUEST['value']:"";
        $ip 	= isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
        if($page == "index"){
            $url = SITE_URL;
        }else{
            $url = SITE_URL.$page;
        }
        if($data!="")$url.="/".$data;
        if($key!="")$url.="/".$key;
        if($value!="")$url.="/".$value;
        $login_id = isset($_SESSION['userInfo']['id'])?$_SESSION['userInfo']['id']:'';
        $date 	= date('Y-m-d');
        if($this->selectCount("select count(*) as count from page_track where ip='".$ip."' and date='".$date."' and page='".$page."' and page_key = '".$key."' and login_id='".$login_id."' and page_data='".$data
            ."' and page_value='".$value."'")==0){
                $insertArray = array(
                    "page"=>$page,
                    "ip"=>$ip,
                    "page_data"=>$data,
                    "page_key"=>$key,
                    "page_value"=>$value,
                    "url"=>$url,
                    "date"=>$date,
                    "login_id"=>$login_id?$login_id:0,
                );
                $this->insert($insertArray, 'page_track');
        }
    }
    public function update ($sql=NULL)
    {
        if($sql!="")
        {
            try {
                $results = mysqli_query($this->connection, $sql);
            } catch (\Throwable $e) {
                $this->error($sql, $e->getMessage());
                return "Invalid Update Query";
            }
            if($results)
            {
                return "Update query success.";
            }
            else
            {
                $this->error($sql);
                return "Invalid Update Query";
            }
        }
        else
            return "Invalid update Query";
    }
    
    function delete($sql=NULL)
    {
        if($sql!="")
        {
            if(mysqli_query($this->connection,$sql))
            {
                $results=mysqli_query($this->connection,$sql);
                if($results!='')
                    return true;
            }
            else
            {
                $this->error($sql);
                return "Invalid Delete Query";
            }
        }
        else
            return "Invalid Delete Query";
    }
    
    function selectCount($sql=NULL)
    {
        
        if($sql!="")
        {
            if(mysqli_query($this->connection,$sql))
            {
                $row = mysqli_fetch_object(mysqli_query($this->connection,$sql));
                return  $row->count;
            }
            else
            {
                $this->error($sql);return "Invalid count Query";
            }
        }
        else
            return "Invalid count Query";
            
    }
    
    function select_count ($sql=NULL)
    {
        if($sql!="")
        {
            $count = 0;
            $data  = array();
            if(mysqli_query($this->connection,$sql))
            {
                $results=mysqli_query($this->connection,$sql);
                while ( $row = mysqli_fetch_object($results))
                {
                    $count++;
                }
                return $count;
            }
            else
            {
                $this->error($sql);return "Invalid Select Query";
            }
        }
        else
            return "Invalid Select Query";
    }
    
    function error($text='')
    {
        $no = mysqli_errno($this->connection);
        $msg = mysqli_error($this->connection);
        if ($msg=="" && $text!="")
            $msg=$text;
        $today = date("D M j G:i:s T Y");

        $message = "Mysql error occurs as per following reference<br><br>";
        $message.= "<hr><font face=verdana size=2>";
        $message.= "<b>Error Date-Time :</b> $today<br><br>";
        $message.= "<b>Custom Message :</b> $text<br><br>";
        $message.= "<b>Error Number :</b> $no<br><br>";
        $message.= "<b>Error Message	:</b> $msg<br><br>";
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $fileReq = isset($_REQUEST['file']) ? $_REQUEST['file'] : '';
        $ipAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $message.= "<b>Error URL	:</b> ".$requestUri."<br><br>";
        $message.= "<b>File	:</b> ".$fileReq."<br><br>";
        $message.= "<b>IP	:</b> ".$ipAddr."<br><br>";
        $message.= "<hr></font>";
        echo $message;
    }
    
    
}

?>