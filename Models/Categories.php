<?php namespace Models;

require_once('Orm/Orm.php'); 

class Categories{

  protected static $table = 'Categories';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new ORM();
  }

}

?>