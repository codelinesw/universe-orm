<?php namespace Models;

require_once('Orm/Orm.php'); 

class Products{

  protected static $table = 'Products';
  private $data = [];
  private $Orm;

  public function __construct(){
      $this->data = null;
      $this->Orm = new ORM();
  }

}

?>