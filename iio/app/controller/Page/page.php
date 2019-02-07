<?php

// -- @@ Navigation Controller :
require_once( IIO . 'app/controller/Nav/nav.php');

// -- @@ Components Controller :
require_once( IIO . 'app/controller/Component/components.php');

class PAGE {

    public function __construct($page,$config){
      $this->render = $this->Parse($page,$config);
    }

    public function __toString(){
      return $this->render;
    }


    private function Parse($page,$config){

      $var = $config['iio-comp-var'];
      $template = $config['assets-route'];
      $is = $config['iio-start'];
      $ie = $config['iio-end'];

      $render = new COMPONENTS;

      $components = $render->renderComponentItems($page,$var);

      // -- Conditions :
      if($config['cond']!==''){
        $components = $render->parseConditions($components,$config['cond']);
      }

      $components = $render->cleanCond($components);

      $comps = explode('/>',$components);
      $comp_count = count($comps);

      $comp = [];
      $page = '';
      $styles = '';

      for($c=0;$c<$comp_count;$c++){

        $this_comp = $render->parseComponent($comps[$c]);

        if($this_comp!=''){

          // -- Get Contents of this Component :
          $component = file_get_contents( IIO . 'components/'.$this_comp.'.html');

          // -- Get Component Style :
          $component = $render->cleanStyle($component);

          // -- Return Cleaned Component :
          $page .= $component;

        }
      }


      // TODO: Create function :
      //
      $page = str_replace($is.'assets-route'.$ie, $template, $page);

      // -- Render All Navs:
      $navs = $config['nav'];
      $navs_count = count($navs);
      $nav_slug = array_keys($navs);
      for($n=0;$n<$navs_count;$n++){
        $nav_name = $nav_slug[$n];
        $page = str_replace($is.'nav:'.$nav_name.$ie, new NAV($config,$nav_name), $page);
      }

      $vars = $config['vars'];
      $var_count = count($vars);

      for($v=0;$v<$var_count;$v++){

        // -- Replace Page Variables with Data/Content :
        $page = str_replace($is.$vars[$v]['name'].$ie, $vars[$v]['data'], $page);

      }

      // -- This will need to be extended :
      $page = $render->cleanTags($page,$is,$ie);

      // -- Get the full URI :


      return $page;


    }














}
