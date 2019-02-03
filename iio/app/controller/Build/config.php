<?php

class CONFIG {

  function __construct($route){
    $this->config = $this->getConfig($route);
  }

  function __toString(){
    return $this->config;
  }

  private function getConfig($route){

      // -- Get Config from Decoded JSON :
      $conf = json_decode(CONFIG,true);

      $iio_var_start = $conf['sys-conf']['iio-start'];
      $iio_var_end = $conf['sys-conf']['iio-end'];
      $iio_comp_var = $conf['sys-conf']['iio-comp-var'];

      $assets_route = $conf['assets-route'];

      $vars = [];
      $vars[] = array('name' => 'page-title', 'data' => '{{%:title}}'.$conf['title']);
      $vars[] = array('name' => 'author', 'data' =>  $conf['author']);
      $vars[] = array('name' => 'logo', 'data' => $conf['logo']);
      $vars[] = array('name' => 'meta-desc', 'data' => $conf['desc']);
      $vars[] = array('name' => 'meta-desc-short', 'data' => $conf['desc-short']);

      $hooks = $conf['hooks'];
      $hooks_count = count($hooks);

      $custom_view = '';
      $custom_cond = '';

      for($h=0;$h<$hooks_count;$h++){
        if($hooks[$h]['name']==$route[1]){
          $hook_controller = require_once( IIO . 'controllers/'.$hooks[$h]['controller'].'.php');
          $custom_vars = new $hooks[$h]['controller-name']($route);
          $custom_view = $hooks[$h]['view'];
        }
      }

      if(isset($custom_vars)!=''){
        $custom_vars = json_decode($custom_vars,true);
        $cv = $custom_vars['vars'];
        $cv_count = count($cv);
        $custom_cond = $custom_vars['cond'];
        for($c=0;$c<$cv_count;$c++){
          $vars[] = $cv[$c];
        }
      }


      // - Main Nav Config:
      $nav = file_get_contents( IIO . 'config/nav/main.json' );
      $nav = json_decode($nav,true);

      $navs = array('main-nav' => $nav['items']);

      $config = array(
         'iio-start' => $iio_var_start,
         'iio-end' => $iio_var_end,
         'iio-comp-var' => $iio_comp_var,
         'assets-route' => $assets_route,
         'nav' => $navs,
         'vars' => $vars,
         'cond' => $custom_cond,
         'view' => $custom_view
      );

      return json_encode($config);

  }


}
