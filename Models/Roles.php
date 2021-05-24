<?php namespace Models;

require_once('Orm/Orm.php'); 

class Roles{

  protected static $table = 'Roles';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new ORM();
  }

}

?>