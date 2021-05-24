<?php namespace Models;

require_once('Orm/Orm.php'); 

class Users{

  protected static $table = 'Users';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new ORM();
  }

}

?>