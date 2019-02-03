<?php

// -- @@ Mongo :
require_once('mongo/mongo.php');


class DATABASE {

  function __construct($config,$payload){
    $this->DB = $this->Build($config,$payload);
  }

  function __toString(){
    return $this->DB;
  }

  private function Build($config,$payload){
     $db = $config['database']['type'];
     return 'ok';
  }

  private function MongoDB($payload){


  }


}
