<?php


class MONGO_DB {

  function __construct($query){
    $this->Mongo = $this->Build($query);
  }

  function __toString(){
    return $this->Mongo;
  }

  private function Build($query){

    $mongo = new MongoDB\Driver\Manager('mongodb://10.0.2.15:27017');

    $filter = array();
    $options = array();
    $arr = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $mongo->executeQuery('aaa.website', $query);

    foreach ($cursor as $doc) {
      $arr[] = $doc;
    }

    return json_encode($arr);

  }

}
