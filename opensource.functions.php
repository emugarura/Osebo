<?php
// Version: 3.0
function getinfo($option='all'){ 
$remoteinfo="================= DETAILS ================ 
Time => ".date("r")."
IP => ". $_SERVER['REMOTE_ADDR']."
Browser => " . $_SERVER['HTTP_USER_AGENT'] . "
File => " . $_SERVER['PHP_SELF'] . "
Server => " . $_SERVER['SERVER_NAME'] . "
Method => " . $_SERVER['REQUEST_METHOD'] . "
Query => " . $_SERVER['QUERY_STRING'] . "
HTTP Language => " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "
Referer => " . $_SERVER['HTTP_REFERER'] . "
URL => " . $_SERVER['REQUEST_URI']."\n\n";

$post = "============== POST VARIABLES ============ \n".
(($_POST) ? print_r($_POST, true) . "\n\n" : $post="POST IS EMPTY\n\n");

$files = "============== FILES VARIABLES ============ \n".
(($_FILES) ? print_r($_FILES, true) . "\n\n" : $files="FILES IS EMPTY\n\n");

$cookies = "============ COOKIE VARIABLES =========== \n".
(($_COOKIE) ? print_r($_COOKIE, true) . "\n\n": $cookies ="NO COOKIES\n\n");

$sql=(mysql_error()) ? "================ SQL ERROR ================ \n".mysql_error() : '';

$backtrace="================== PHP DEBUG ================ \n".print_r(debug_backtrace(),true); 

$lasterror="============== PHP LAST ERROR ============ \n".print_r(error_get_last(),true);
    
        switch($option){
                case 'url':
                        $info = "URL: {$_SERVER['REQUEST_URI']}";
                        break;
                        
                case 'post':
                        $info = $post;
                        break;
        
                case 'cookies':
                        $info = $cookies;
                        break;
                        
                case 'remote':
                        $info = $remoteinfo;
                        break;
                        
                case 'php':
                        $info = $lasterror.$backtrace;
                        break;
                        
                case 'sql':
                        $info = $sql;
                        break;

                case 'files':
                        $info = $files;
                        break;
                        
                case 'all':
                default:
                        $info = $url.$post.$remoteinfo.$cookies.$files;
                        break;
        }
        
        return $info;

} 

//OPTIMIZED OCT 2010 FOR NOT OPENING CONNECTION PROBLEM
//THIS SHOULD BE IMPROVED
function mysql_clean($string, $type=false) {
  $string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;
	$string = mysqli_real_escape_string(mysqli_connect(SERVER,USER,PASSWORD,DATABASE),$string);
	
	if ($type == "wildcard")
	{
		return addcslashes($string, "%_"); 
	}
	else
	{
		return "'{$string}'";
	}
}

function html($string,$clean=true) {
  if (!mb_check_encoding($string, "UTF-8")) {
    $string = utf8_decode($string);
  }
  $convert = array('’' => "'", '“' => "'", '”' => "'", '–' => "-");
  $string = strtr($string, $convert);
  $string = nl2br(htmlspecialchars($string, ENT_QUOTES, "UTF-8"));
	if ($clean) {
	    $string = mysql_clean($string);
	}
	return($string);
}

function html_clean($string,$clean=true) {
  if (!is_defined(TAGS)) {
    die("Please define allowed tags first");
  }
  $string = strip_tags($string, TAGS);
	if ($clean) {
	    $string = mysql_clean($string);
	}
	return($string);
}


//OPTIMIZED JUL 13 2010
//recibe dos parametros: uno de tipo cadena (el texto a cortar) y otro de tipo entero (la cantidad de caracteres a cortar)
function smartcut($string, $length,$suffix="..."){ //comentarios por Soraya aka sora
        
        if($string && (int)$length){ //verifica si recibe los dos parametros cadena y entero
                
                if(strlen($string) >= $length){ //si la cadena es mayor o igual a la cantidad de caracteres a cortar

			if(strpos($string," ")){ //si hay espacios en la cadena
	
        	                while($string[$length]!=" "){ //mientras el caracter no sea un espacio
                	          $length--;//si no sabes lo que hace no estas en el lugar correcto
                        	}

	                        if(in_array($string[$length-1],array(",",":",";",".","-"))){
        	                  $length--;
                	        }
                        
                        	return substr($string, 0, $length).$suffix;
			} else { //cuando es una sola palabra pero excede el limite, a cortarla
				return substr($string,0,$length).$suffix;
			}
                        
                } else {
                        return $string;
                }
                
        } else {
                return false;
        }
}

class DB{
  private $connection;
  var $insert_id;
  var $affected_rows;

	function __construct($server = false,$user = false,$password = false,$database = false, $action = false) {
    $this->connection = new mysqli("p:".SERVER,USER,PASSWORD,DATABASE);          
    if (mysqli_connect_errno()) {  
        if ($action == "mailerror") {
          mail("sysadmin@ibisservices.com", "MySQL Connection Error", $this->connection->connect_error() . "\n\n" . getinfo(), 
            "From: automail@ibisservices.com");
        } else {
          die("Connection Error: " . $this->connection->connect_error);
        }
    }
    $this->connection->set_charset("utf8");
  }
   
  function query($query, $print = false, $action = false){		
      if($query){
        $result = $this->connection->query($query) or 
          $action == "mailerror" ? 
            mail("sysadmin@ibisservices.com", "MySQL Query Error", mysqli_error($this->connection) . "\n\n" . getinfo(), 
              "From: automail@ibisservices.com") : 
            die("Query:\n$query\n\nError:\n" . mysqli_error($this->connection) . "\n\n");

        $this->insert_id = $this->connection->insert_id;
        $this->affected_rows = $this->connection->affected_rows;
        
        if($print && LOCAL && defined("LOCAL")) {
            echo "<div style='
            background:#333; 
            border:2px solid #fff;
            padding:5px; 
            position:fixed;
            font-weight:bold;                  
            top:0;
            color:#fff;
            width:100%;
            left:0;
            z-index:100000;
            '>
                $query
              </div>";
        }
      
        return new DB_Result($result);
      } else {	    
        trigger_error("Function query(): The query is empty. ",E_USER_ERROR);
      }

  }	
  
  function insert ($table,$data,$print = false, $action = false){
      
      //Prepare columns in query
      foreach($data as $key => $value) {
        $columns.= "`" . $key . "`,";
      }
      $columns = substr($columns, 0, -1);

      //Prepare values in query
      foreach($data as $key => $value) {
        $values.= $value.",";
      }
      $values = substr($values, 0, -1);

      //Order columns and values in query
      $query=	"INSERT INTO $table ( $columns ) VALUES ( $values )";
      self::query($query,$print, $action);
  }
  
  function update($table,$data,$condition,$print = false){

      foreach($data as $key => $value) {
        $values .= "`$key` = $value,";
      }
      $values = substr($values, 0, -1);
      

      $query=	"UPDATE $table SET $values WHERE $condition";
      self::query($query,$print);
  }

  function prepare($string,$wildcard = false){
    $string = nl2br(htmlentities($string, ENT_QUOTES, "UTF-8"));
    $string = $this->connection->real_escape_string($string);

    if ($wildcard){
      return addcslashes($string, "%_"); 
    } else {
      return "'$string'";
    }
  }
}

class DB_Result{
	var $resource;
	var $pointer = 0;
	var $num_rows;
	
	function __construct($resource){
		$this->num_rows = $resource->num_rows;
        $this->affected_rows = $resource->affected_rows;
        if($resource->num_rows==1){
            $this->resource = $resource->fetch_assoc();		
        } else {
            $this->resource = $resource;
        }
     }
     function fetch(){
         if($this->resource){
          if(is_array($this->resource)){
              if($this->pointer){
                  return false;
              } else {
                  $this->pointer = 1;
                 return $this->resource;
              }
          } else {
            return $this->resource->fetch_assoc();		
          }
      } else {
        return false;
      }
	}
	
	function reset(){
		if (!is_array($this->resource)) {
			$this->resource->data_seek(0);
      return true;
		} else {
      $this->pointer = 0;
      return true;  
    }
	}

	public function  __get($name) {  
    // check if the named key exists in our array  
    if(is_array($this->resource) && array_key_exists($name, $this->resource)) {  
	    // then return the value from the array  
  		return $this->resource[$name];  
    }  else {
      return false;  
    }
	}  
	
}

?>
