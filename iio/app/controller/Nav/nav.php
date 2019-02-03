<?php

class NAV {

  function __construct($conf){
    $this->nav = $this->build_nav($conf);
  }

  function __toString(){
    return $this->nav;
  }

  private function build_nav($conf){
    $nav = $conf['nav']['main-nav'];
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
