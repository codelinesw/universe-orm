<?php namespace Controllers;

require_once('Models/Stores.php');
require_once('Utils/Message.php');
require_once('Utils/States.php');

use Models\Stores;
use Utils\Message;
use Utils\States;

class StoresController{

    public $Stores;

    public function __construct(){        
        $this->Stores = new Stores();
    }

    public function index(){
        echo json_encode(array('message' => 'i am index'));
    }

    public function list(){
        $this->Stores->list();
    }

    public function add(){        
        $Object = file_get_contents('php://input');
        $request = json_decode($Object,true);
        $Message = new Message();
        if(!isset($request['document']) && !isset($request['name']) && !isset($request['address'])){
            $Message->setMessage(States::EMPTY,'Ups! hacen falta parametros para ejecutar la solicitud')->getMessage();            
        }else if(!isset($request['document'])){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar un documento para la tienda para poder continuar')->getMessage();
        }else if(!isset($request['name']) ){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar un nombre para la tienda para poder continuar')->getMessage();
        }else if(!isset($request['email'])){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar un correo electrónico de contacto')->getMessage();
        }else if(!isset($request['address'])){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar la direccion donde se encuentra ubicada la tienda')->getMessage();
        }else{            
            
            $name = isset($request['name']) ? $request['name'] : '';
            $document = isset($request['document']) ? $request['document'] : '';
            $email = isset($request['email']) ? $request['email'] : '';
            $address = isset($request['address']) ? $request['address'] : '';
            $cellPhoneNumber = isset($request['cellPhoneNumber']) ? $request['cellPhoneNumber'] : '';
            $state = 'A';
            $this->Stores->set('name',$name);
            $this->Stores->set('document',$document);
            $this->Stores->set('email',$email);
            $this->Stores->set('cellPhoneNumber',$cellPhoneNumber);
            $this->Stores->set('address',$address);
            $this->Stores->set('state',$state);            
            $this->Stores->add();
        }
    }

    public function update(){
        $Object = file_get_contents('php://input');
        $request = json_decode($Object,true);
        $Message = new Message();
        if(!isset($request['document']) && !isset($request['name']) && !isset($request['address'])){
            $Message->setMessage(States::EMPTY,'Ups! hacen falta parametros para ejecutar la solicitud')->getMessage();            
        }else if(!isset($request['document'])){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar un documento para la tienda para poder continuar')->getMessage();
        }else if(!isset($request['name']) ){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar un nombre para la tienda para poder continuar')->getMessage();
        }else if(!isset($request['address'])){
            $Message->setMessage(States::EMPTY,'Ups! debes de ingresar la direccion donde se encuentra ubicada la tienda')->getMessage();
        }else{            
            $bossName = isset($request['bossName']) ? $request['bossName'] : '';
            $documentBossName = isset($request['documentBossName']) ? $request['documentBossName'] : '';
            $email = isset($request['email']) ? $request['email'] : '';
            $cellPhoneNumber = isset($request['cellPhoneNumber']) ? $request['cellPhoneNumber'] : '';
            $state = isset($request['state']) ? $request['state'] : 'A';            
            $dateUpdated = date('Y-m-d h:m:s');
            $idStores = isset($request['idStores']) ? $request['idStores'] : '0';
            $this->Stores->set('document',$request['document']);
            $this->Stores->set('name',$request['name']);
            $this->Stores->set('bossName',$bossName);
            $this->Stores->set('documentBossName',$documentBossName);
            $this->Stores->set('address',$request['address']);
            $this->Stores->set('email',$email);
            $this->Stores->set('cellPhone',$cellPhoneNumber);            
            $this->Stores->set('state',$state);
            $this->Stores->set('dateUpdated',$dateUpdated);    
            $this->Stores->update($idStores);
            
        }
    }

    public function delete(){
        $Object = file_get_contents('php://input');
        $request = json_decode($Object,true);
        $Message = new Message();
        if(!isset($request['idStores'])){
            $Message->setMessage(States::EMPTY,'Ups! hacen falta parametros para ejecutar la solicitud')->getMessage();            
        }else{
            $idStores = isset($request['idStores']) ? $request['idStores'] : '0'; 
            $this->Stores->set('idStores',$idStores);  
            $this->Stores->delete();
        }
    }

}

?>