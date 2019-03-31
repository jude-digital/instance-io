<?php

class DEFAULT_CONTROLLER {

  function __construct($route){
      $this->build = $this->DEFAULT_BUILD($route);
  }

  function __toString(){
    return $this->build;
  }

  private function DEFAULT_BUILD($route){

    // -- Start Variable Array:
    $vars = [];

    // -- EXAMPLE:

    // -- This is an example of a custom variable:
    $vars[] = array('name' => 'title', 'data' => 'This is the page title');

    // -- This is a custom condition examople:
    $cond = 'example';

    // -- Build Variable Array:
    $vars = array('cond' => $cond, 'vars' => $vars);

    // -- Must return encoded JSON format :
    return json_encode($vars);

  }


}
