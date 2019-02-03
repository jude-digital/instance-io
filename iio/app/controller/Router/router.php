<?php

class ROUTER {

  function __construct(){
    $this->router = $this->getRoute();
  }

  function __toString(){
    return $this->router;
  }

  private function getRoute(){

    // -- Get URI and create array:
    $U = $_SERVER['REQUEST_URI'];
    $U = explode('/',$U);

    // TODO: Remove first array item (to kill blank):

    // -- Return Route Array:
    return json_encode($U);

  }
}
