<?php
require_Once('Orm.php');

class Users{

    protected static $table = 'u_users';
    private $data = [];
    private $Orm;

    public function __construct(){
        $this->data = null;
        $this->Orm = new ORM();
    }

    public function get($property){
        return $this->data[$property];
    }

    public function set($property,$value = ''){
        $this->data[$property] = $value;
    }

    public function getFields(){
        return $this->data;
    }

    public function add(){
        $this->Orm->addTable(static::$table);
        $this->Orm->addFields($this->data);
        echo $this->Orm->insert();
    }

    public function list($columns){
        $this->Orm->addTable(static::$table);        
        $this->Orm->addFields($columns);
        $this->Orm->select();
    }
}



?>