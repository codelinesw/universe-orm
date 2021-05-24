<?php namespace Models;

require_once('Orm/Orm.php'); 

class Sales{

  protected static $table = 'Sales';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new ORM();
  }

}

?>