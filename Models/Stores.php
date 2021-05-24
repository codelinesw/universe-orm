<?php namespace Models;

require_once('ORM/Orm.php');

use ORM\Orm as Orm;

class Stores{

  protected static $table = 'Stores';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new Orm();
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

  public function list($columns = null){
      $this->Orm->addTable(static::$table);        
      $this->Orm->addFields($columns);
      $this->Orm->select();
  }

  public function listforid($id){
    $this->Orm->addTable(static::$table);
    $this->Orm->addWhere('id_user','=',$id);
    $this->Orm->select($this->data);
  }  

  public function update($id){
    $this->Orm->addTable(static::$table);
    $this->Orm->addFields($this->data);
    $this->Orm->update();
    $this->Orm->addWhere('idStores','=',$id);
    echo $this->Orm->execQuery();
  }

   public function delete(){
    $this->Orm->addTable(static::$table);    
    $this->Orm->addIn($this->data);
    $this->Orm->delete();
    echo $this->Orm->execQuery();
   }

}

?>