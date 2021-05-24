<?php namespace Utils;

class Message{

    private $state;
    private $message;

    public function __construct(){

    }

    public function setMessage($stateTmp,$messageTmp){
        $MessageTmp = new Message();
        $MessageTmp->state = $stateTmp;
        $MessageTmp->message = $messageTmp;
        return $MessageTmp;
    }

    public function getMessage(){
        echo json_encode(array(
            'state' => $this->state,
            'message' => $this->message
        ));
    }
}

?>