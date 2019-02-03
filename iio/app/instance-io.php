<?php

// -- @@ Generate Defines :
require_once( 'defines/defines.php' );

// -- @@ Pull in Dependencies :
require_once( IIO . 'app/controller/index.php' );

class IIO {

  public function __construct($build_type,$route){

    switch($build_type){
      case 'service' :
        $build = $this->Build_Live(
          json_decode($route,true)
        );
      break;
      case 'live' :
      default :
         $build = $this->Build_Live(
           json_decode(new ROUTER,true)
         );
      break;
      case '404' :
        $build = $this->Build_404();
      break;

    }

    $this->instance = $build;


  }

  public function __toString(){
    return $this->instance;
  }


  private function Build_404(){
    $config =
      json_decode(
          new CONFIG(null),
          true
    );

    $page = $this->renderPage(
      file_get_contents(
         IIO . 'views/404.iio'
      ),
      $config
    );

    // -- Build to cache :
    $this->cachePage(null,$page,'404');

    return $page;

  }

  private function Build_Live($route){

    // -- Get Config :
    $config =
      json_decode(
          new CONFIG($route),
          true
    );


    if($route[1]=='home'){
      header('location: /');
      exit;
    }

    if($config['view']!==''){
      $view = $config['view'];
    }else {
      if($route[1]!==''){
        $view = $route[1];
      }else {
        $view = 'home';
      }
    }


    // -- Render Page (if exists) :
    $page = ( IIO . 'views/' . $view . '.iio');


    if(file_exists($page)){

      $page = $this->renderPage(
        file_get_contents($page),
        $config
      );

      // -- Build to cache :
      $this->cachePage($route,$page,'page');

    }else {

      $page = $this->renderPage(
        file_get_contents(
           IIO . 'views/404.iio'
        ),
        $config
      );

    }


    return $page;

  }


  private function renderPage($page,$config){
      return ' ' . new PAGE($page,$config);
  }



  // -- TODO: Create Separate Class/Controller :
  private function cachePage($route,$page,$type){

      $cache_location = IIO . 'cache';

      if($type=='404'){
        file_put_contents( $cache_location . '/404.html', $page);
      }else {
        $dir_count = (count($route)-1);
        $path = '';

        for($i=0;$i<=$dir_count;$i++){

            $path = $path . '/' . $route[$i];
            $cache_path = $cache_location.$path;

            if(!is_dir($cache_path)){
              mkdir($cache_path);
            }

            if($i==$dir_count){
              file_put_contents( $cache_path . '/index.html', $page);
            }

        }
      }


  }



}
