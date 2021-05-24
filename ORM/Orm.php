<?php namespace ORM;

require_once('Models/Connection.php');
require_once('Utils/States.php');

use Models\Connection;
use Utils\States;

class Orm{

	private $Connection;	
    private $query = "Ss";
	private $stmt;
    private $table;
    private $alias;
    private $queryType;
    private $fields;
    private $where = [];
    private $join;
    private $include_count;
    private $group_by;
    private $order_by;
    private $limit;
    private $in;
	private $needJson = true;
	private $needReplace = false;
	private $indexToReplace;

    public function __construct(){
		$this->Connection = new Connection();
    }

    public function set($attr,$value){
        $this->$attr = $value;
    }

	/**
	 * This method add one condition for setence SQL
	 * @param String
	 * @return clausule sql
	*/
	
	public function make_Condition($condition){		
		$result = " WHERE ";
		if(isset($condition)){
			if(is_array($condition) && sizeof($condition) > 0){
				$count = 0;						 
				foreach($condition as $item){
					if($count > 1){
						$result .= " AND ".$item[0]." ".$item[1]." ".$item[2];
					}else{
						$result .= $item[0]." ".$item[1]." ".$item[2];
					}
				}
			}
		}
		
		if(isset($this->in)){
			if(is_array($this->in) && sizeof($this->in) > 0){
				$key = array_keys($this->in);
				$result .=  $key[0]." IN(".implode(",",$this->in[$key[0]])." )";
			}					
		}

		$this->query = $this->query.' '.$result;
		$this->stmt = $this->Connection->_prepare_($this->query);

	}

	/**
	 * This method add one order for sentence SQL
	 * @param Array or String
	 *  @return type order
	*/
	
	public function make_Order($order){
		$result = "";
		if(isset($rorder)){
			if(is_array($order)){
				$result .= " ORDER BY ". implode(",", $order) .$this->order_by;
			}else{
				$result .= " ORDER BY ". $order .$this->order_by;
			}
		}
		return $result;
	}


	/**
	 * This method groups the fields for the SQL query
	 * @param Array of String
	 * @return type of order
	*/
	public function make_GroupBy($group){
		$result = "";
		if(isset($group)){
			if(is_array($group)){
				$result .= " ORDER BY ". implode(",", $group);
			}else{
				$result .= " ORDER BY ". $group;
			}
		}
		return $result;
	}

	/**
	 * This method relates two or more tables to bring the information
	 * @param Array or String
	 * @return  the relation of one or more tables
	*/
	public function make_Join($join){
		$result = "";
		if(isset($join)){
			if(is_array($join)){
				foreach ($join as $key => $value) {
					$result .= substr($key, 0,strpos($key," "))." JOIN ".$join[$key][0]." ON ".$join[$key][0].".".$join[$key][1]." = ".$join[$key][2].".".$join[$key][3]." ";
				}
			}else{
				$result = $this->typejoin." JOIN ".$this->join;
			}

		}

		return $result;
	}


	/**
	 * This method adds a limit for result to the information
	 * @param Array or String
	 * @return  the one o more limit for result
	*/

	public function make_Limit($limit){
		$result = '';
		if(isset($limit)){
			if(is_array($limit)){
				$result = 'LIMIT '.implode(',',$limit);
			}else{
				$result = 'LIMIT '.$limit;
			}
		}

		return $result;
	}

    public function prepareSelect(){

        $this->query = "SELECT ";

        if(isset($this->include_count)){
            $this->query .= "COUNT(*) ";
        }
        
        if(is_array($this->fields)){
			if(sizeof($this->fields) > 0){
				$this->query .= implode(",", $this->fields);			
			}else{
				$this->query .= $this->fields;
			}
        }else{
            $this->query .= "*";
        }

        $this->query .= " FROM ";
		if(isset($this->table)){
			$this->query .= $this->table." ".$this->alias;
		}

		$this->query .= $this->make_Join($this->join);
		//$this->query .= $this->make_Condition($this->where);
		$this->query .= $this->make_GroupBy($this->group_by);
		$this->query .= $this->make_Order($this->order_by);
		$this->query .= $this->make_Limit($this->limit);		
		$this->stmt = $this->Connection->_prepare_($this->query);
        //echo $this->execQuery($this->stmt);
        
    }

    public function prepareInsert(){

        $this->query = "INSERT INTO ";
		$keys = array();
		if(isset($this->fields)){
			if(is_array($this->fields)){
				$keys = array_keys($this->fields);
				$this->query .= $this->table."(".implode(",", $keys).") ";
			}else{
				$this->query .= $this->table.$this->fields;
			}
		}

		if(isset($this->table) && isset($this->fields) && is_array($this->fields) && sizeof($this->fields) > 0){
            $indices = implode(",:",$keys);
            $indices = ':'.$indices;
			$this->query .= " VALUES (".$indices.")";
		}
				
		$this->stmt = $this->Connection->_prepare_($this->query);
        return $this->execQuery();
	}
	
	public function prepareUpdate(){
		
		$this->query = "UPDATE ";

		if(isset($this->table)){
			$this->query .= $this->table." SET ";
			if(is_array($this->fields)){
				$fields = array_keys($this->fields);
				$size = sizeof($this->fields);				
				for($i = 0; $i < $size; $i++){
					if(!empty($this->fields[$fields[$i]])){
						if($i <= ($size-2)){
							$this->query .= $fields[$i].'=:'.$fields[$i].",";
						}else{
							$this->query .= $fields[$i].'=:'.$fields[$i];
						}
					}					
				}
			}
		}
		
		
	}

	public function prepareDelete(){
		$this->query = "DELETE FROM ";
		if(isset($this->table)){
			$this->query .= $this->table;			
		}	
	}

    public function setParams($stmt){
        if(is_array($this->fields) && sizeof($this->fields) > 0){
            $indices = array_keys($this->fields);
			$size = sizeof($this->fields);
            for($i = 0; $i < $size; $i++){
                if($i >= $size){
					break;
				}
				if(!empty($this->fields[$indices[$i]])){
					$stmt->bindParam(':'.$indices[$i],$this->fields[$indices[$i]]);
				}				
            }
        }
    }

    public function select(){
        $this->addQueryType("SELECT");
        return $this->prepare();
	}
	
	public function insert(){
        $this->addQueryType("INSERT");
        return $this->prepare();
    }

    public function update(){
        $this->addQueryType("UPDATE");
        return $this->prepare();
    }

    public function delete(){
        $this->addQueryType("DELETE");
        return $this->prepare();
    }

    /**
	 * This method allows you to encrypt the information that is sent
	 * @param  [type] $data  This parameter is the result of our query
	 * @param  [type] $index Get the index where you need to encrypt the information in the array, which represents the information obtained from the query to the database
	 * @return [type]  all indexed data already encrypted
	 */
	public function replaceData($data,$index){
		// CBC has an IV and thus needs randomness every time a message is encrypted
		$method = 'AES-256-CBC';
		// simple password hash
		$password = 'a1c4d3mi';
		$key = hash('sha256', $password);
		$index = $this->replace;
		if(isset($index)){
			print_r($index);
			if(is_array($index)){				
				foreach ($index as  $value) {
					if($value == "r_rol_id"){
						$data[$value] = $this->generateToken().$data[$value].$this->generateToken();
					}else{
						$data[$value] = $this->generateToken().$this->encrypt($data[$value], $key, $method);
					}					
				}
			}else{
				if($index == "description"){
					$lines = '';
					foreach ($data as $key => $value) {
					   $file = file($value[$index]);
					   foreach ($file as $line) {
						$lines .= $line;
					   }
				 	   $data[$key][$index] = $lines;
					}
					return json_encode($data);
				}else{
					$data[$index] = $this->generateToken().$this->encrypt($data[$index], $key, $method);
					return json_encode([$data]);
				}
			}
		}
	}

    /**
	 * This method checks if there is data for this query, in case that yes, then
	 * return data are json type
	 * @param  [type] PDOstatement
	 * @return [type] json type
	*/
	public function jsonObject($stmt){
		if($stmt->rowCount() > 0){
			if($this->needReplace){
				if($needJson){
					return $this->replaceData($stmt->fetchAll(\PDO::FETCH_ASSOC),$this->indexToReplace);
				}else{
					return $this->replaceData($stmt->fetch(),$this->indexToReplace);
				}
			}else{
				return json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
			}
			
		}else{
			return json_encode(array());
		}
	}

	/**
	 * This method return the information in an array
	 * @param  [type] PDOstatement
	 * @param  [type] params for query
	 * @return [type] Array 
	*/
	public function getData($stmt){		
		if($stmt->rowCount() > 0){
			if($this->needReplace){
				return $this->replaceData($stmt->fetch(),$this->indexToReplace);
			}else{				
				return $stmt->fetch();
			}			
		}else{
			return json_encode(array());
		}
	}

    public function execQuery(){

		if($this->queryType == 'UPDATE' || $this->queryType == 'DELETE'){
			$this->make_Condition($this->where);
		}

        if(isset($this->fields) && sizeof($this->fields) > 0){			
            $this->setParams($this->stmt);
        }
		
		$this->stmt->execute();
        if($this->queryType != "SELECT"){
			if($this->stmt->rowCount() > 0){
				return json_encode(array(
					'state' => States::SUCCESS,
					'message' => 'La solicitud se ejecuto con exito'
				));
			}else{
				return json_encode(array(
					'state' => States::FAILED,
					'message' => $this->stmt->errorInfo()[2]
				));				
			}
		}

		if($stmt && $this->needJson){
			return $this->jsonObject($this->stmt);
		}else{		
			return $this->getData($this->stmt);
        }
        
	}
	
    public function prepare(){

        switch($this->queryType){
            case "SELECT":
                $this->prepareSelect();
				return null;
                break;
            case "INSERT":
                return $this->prepareInsert();				
                break;
            case "UPDATE":
                $this->prepareUpdate();
				return null;
                break;
            case "DELETE":
                $this->prepareDelete();
				return null;
                break;
            default:
                $this->prepareSelect();
                break;
        }

    }

    public function addQueryType($type){
        $this->queryType = $type;
    }

    public function addTable($value){
        $this->set('table',$value);
    }
 
    public function addFields($value){
		if(is_array($value)){
			if(sizeof($value) > 0){
				if(array_key_exists('0',$value)){
					$fields = explode(',', implode(',',$value));
					$this->set('fields',$fields);
				}else{				
					$this->set('fields',$value);
				}				
			}
		}
        
    }
 
    public function addWhere($column,$operator,$value){
		$value = addslashes($value);		
        array_push($this->where,[$column,$operator,$value]);		
    }
 
    public function addOrder($clause,$value){
        $this->set($clause,$value);
    }
 
    public function addGroupBy($clause,$value){
        $this->set($clause,$value);
    }
 
    public function addTypeJoin($clause,$value){
        $this->set($clause,$value);
    }
 
    public function addJoin($clause,$value){
        $this->set($clause,$value);
    }
 
    public function addIn($value){
        $this->set('in',$value);
    }

    public function addLimit($value){
        $this->set('limit',$value);
    }

	public function encryptIndex($index){
		$this->indexToReplace = $index;
	}

	public function json(){
		$this->needJson = true;
	}

}



?>