<?php

// -- @@ Generate Defines :
$configuration = file_get_contents('../../config/app.json');
require_once( '../defines/defines.php' );

// -- Pull In sitemap :
$map = file_get_contents( IIO . 'config/sitemap.json');

// -- Include InstanceIO :
require_once( IIO . 'app/instance-io.php');


class BUILD_IIO {

  public function __construct($map){
    $this->response = $this->Build($map);
  }

  public function __toString(){
    return $this->response;
  }

  private function Build($map){
    $map = json_decode($map,true);
    $mapCount = count($map);
    $pathBuild = [];

    for($i=0;$i<$mapCount;$i++){
      $this_obj = $map[$i];
      $pathCount = count($this_obj['pages']);

      array_push($pathBuild, '/' . $this_obj['slug']);

      if($pathCount!==0){
         $pathBuild = $this->PathLoop($this_obj['pages'],$pathCount,$pathBuild);
      }
    }

    $this->Build_Pages($pathBuild);

  }


  private function Build_Pages($map){
    $pathCount = count($map);
    for($i=0;$i<$pathCount;$i++){

      $this_map_obj = explode('/',$map[$i]);

      new IIO('service', json_encode($this_map_obj));
    }

    // -- @@ Create Error Pages :
    new IIO('404',null);

  }


  private function PathLoop($obj,$count,$path){

    for($i=0;$i<$count;$i++){

      $this_obj = $obj[$i];
      $pathCount = count($this_obj['pages']);

      array_push($path, '/' . $this_obj['slug']);

      if($pathCount!=0){
        $path = $this->PathLoop($this_obj['pages'],$pathCount,$path);
      }

    }

    return $path;
  }



}



// -- Run the Style Parser :
new BUILD_IIO($map);

//// ------------ ////
