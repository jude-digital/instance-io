<?php

// -- Style Parser Class :

class PARSE_STYLES {

  function __construct($config){
    $this->response = $this->Build($config);
  }

  function __toString(){
    return $this->response;
  }


  private function Build($config){
    $component_files = $this->getDirContents('../../components/');
    $count = count($component_files);

    $styles = '';
    $config = json_decode($config,true);

    $render = new COMPONENTS;

    echo "\n\n----- Rendered the following Component Styles ------\n\n";

    for($i=0;$i<$count;$i++){
      if(is_file($component_files[$i])){
        $file = file_get_contents($component_files[$i]);
        $styles .= $render->renderComponentItems($file,'style');
        echo $component_files[$i]."\n";
      }
    }

    echo "\n-------------------------------------------------\n\n";

    echo "\n\n----- Style Output ------\n\n";

    $styles = $this->minifyCSS($styles);
    $styles = $this->parseStyleVars($styles,$config);

    echo $styles;

    echo "\n\n-------------------------------------------------\n\n";

    $style_file = '../../../public/'.$config['assets-route'].'/css/style.css';

    // -- @@ Make sure the style file exists :
    if(is_file($style_file)){
      $style = file_get_contents($style_file);
    }else {
      $style = '';
    }

    $style .= $styles;

    $handle = fopen($style_file, 'w');
    fwrite($handle, $style);

    return "\n\n".json_encode($component_files)."\n\n";


  }


  private function parseStyleVars($style,$config){

      // -- Parse Asset Router :
      $style = str_replace('{{%:assets-route}}',$config['assets-route'],$style);

      return $style;

  }




  private function minifyCSS($css) {

    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
    }

    $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
    $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
    $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
    $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
    $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
    $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
    $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
    $css = preg_replace('/\p{Zs}+/ims',' ', $css);
    $css = str_replace(array("\r\n", "\r", "\n"), '', $css);

    for ($i=0; $i < count($hit[1]); $i++) {
      $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
    }

    $css = trim($css);

    return $css;
  }




  private function getDirContents($dir, &$results = array() ){
    $files = scandir($dir);
      foreach($files as $key => $value){
          $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
          if(!is_dir($path)) {
              $results[] = $path;
          } else if($value != "." && $value != "..") {
              $this->getDirContents($path, $results);
              $results[] = $path;
          }
      }
      return $results;
    }

}
