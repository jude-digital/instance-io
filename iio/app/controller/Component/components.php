<?php


class COMPONENTS {

  public function parseConditions($page,$cond){
    $conds = $this->renderComponentItems($page,$cond);
    $rendered = $this->renderCond($conds,$page);
    return $rendered;
  }

  public function renderComponentItems($page,$tag){
    $tag = str_replace( array('>','<'), '', $tag);
    $var = '<'.$tag.'>';
    $root = '/'.$var.'(.*?)'.$var.'/s';
    $content = preg_match($root,$page,$instance);

    $conds = '';

    if(isset($instance[1])){
      $conds = $instance[1];
    }

    return $conds;

  }

  public function cleanCond($page){
    return $this->renderCond('',$page);
  }

  public function renderCond($str,$page){
    return preg_replace('/<cond[^>]*>([\s\S]*?)<cond[^>]*>/', $str, $page);
  }

  public function cleanStyle($page){
    return preg_replace('/<style[^>]*>([\s\S]*?)<style[^>]*>/', '', $page);
  }

  public function cleanTags($page,$is,$ie){

    $title_tag = $is.'title'.$ie;

    $tags = array(
      $title_tag
    );

    return str_replace($tags,'',$page);
  }

  public function parseComponent($comp){
      $comp = str_replace('<','',$comp);
      $comp = str_replace(' ','',$comp);
      $comp = str_replace("\n",'',$comp);
      $comp = str_replace(':','/',$comp);
      return $comp;
  }


}
