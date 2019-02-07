<?php

class NAV {

  function __construct($conf,$name){
    $this->nav = $this->build_nav($conf,$name);
  }

  function __toString(){
    return $this->nav;
  }

  private function build_nav($conf,$name){
    $nav = $conf['nav'][$name];
    $count = count($nav);
    $items = '';
    for($c=0;$c<$count;$c++){
      $items .= '<li><a href="'.$nav[$c]['url'].'">'.$nav[$c]['label'].'</a></li>';
    }

    return('
      <ul>
        '.$items.'
      </ul>
    ');
  }

}
