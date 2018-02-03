<?php

/* 	----------------------------------------------------------- */
/* 	----------------------------------------------------------- */
/*

	INSTANCE.IO {}

	//	Welcome to Instance.IO main controller. This file
 	//	renders the template on runtime.

  	// V: 1.4

*/
/* 	----------------------------------------------------------- */
/* 	----------------------------------------------------------- */

class INSTANCE_IO {

	var $config;

	function __construct($config){
        $this->page = $this->returnHTML(
            $this->URI($this->segment()),
            $config['CACHE'],
            $config['EXT'],
            $config['PAGE_EXT'],
            $config['TEMPLATE'],
            $config['HOOKS']
        );
	}
	function __toString(){
		return $this->page;
	}

    // -- URI Function : Renders page URI
    private function URI($URI){

        if(($URI[1]=='index.php')||($URI[1]=='index.html')){
            $PAGE = 'home';
        }else if(isset($URI[1])!=''&&isset($URI[2])==''){
            $PAGE = $URI[1];
        }else if(isset($URI[1])!=''&&isset($URI[2])!=''){
            $PAGE = $URI[1].'__'.$URI[2];
        }else {
            $PAGE = 'home';
        }
        return $PAGE;
    }
    private function removeSlash($string){
        if($string[strlen($string)-1] == '/'){
            $string = rtrim($string,'/');
        }
        return $string;
    }
    private function segment(){
        $url = $this->removeSlash($_SERVER['REQUEST_URI']);
        $url = explode('/',$url);
        unset($url[0]);
        return $url;
	}

	// -- Main return controller. Based on Cache Values.
	private function returnHTML($page,$cache,$ext,$page_ext,$template,$hook_data){
		switch ($cache) {
			 case 0:
			 	$HTML = $this->BUILD($page,$ext,$page_ext,$template,$hook_data,$cache_path,'0');
			    break;
			case 1:
				$HTML = $this->CACHE($page,$ext,$page_ext,$template,$hook_data);
			    break;
			default:
			    $HTML = $this->BUILD($page,$ext,$page_ext,$template,$hook_data,$cache_path,'0');
			    break;
			}
		return $HTML;
	}


	// -- Main Page Build Function (If Not Cache Request)	------- //
    private function BUILD($page,$ext,$page_ext,$template,$hook_data,$cache_path,$cache_status){
	    $VIEW_HEADER = $VIEW_FOOTER = $THIS_CONTENT = '';
			$HEADER = $this->getHeader($ext,$template);
			$CONTENT = $this->getPage($page,$page_ext,$template,$hook_data);
			$FOOTER = $this->getFooter($ext,$template);
			$META = explode('@@',$this->Meta($CONTENT,'get'));

	    $GLOBAL_404 = $this->check404_tag($META);
	    if($GLOBAL_404=='404'){
	        $CONTENT = $this->getPage('404-get',$page_ext,$teplate,$hook_data);
	        $META = explode('@@',$this->Meta($CONTENT,'get'));
	    }

	    $GLOBAL_META = $this->globalMeta($template);
			foreach($META as $ELEM){
				$THIS_TAG = explode(';->',$ELEM);
				$TAG = $THIS_TAG[0];
	            if (in_array($TAG,$GLOBAL_META)){
	                $ITEM = array_search($TAG,$GLOBAL_META);
	                unset($GLOBAL_META[$ITEM]);
	            }
	            if(isset($THIS_TAG[1])!=''){
	                $THIS_CONTENT = $THIS_TAG[1];
	            }
				$THIS_ELEM = str_replace($TAG,'',$THIS_CONTENT);
				$THIS_ELEM = trim($THIS_ELEM);
				switch($TAG){
					case 'Title' :
						if($THIS_ELEM!=''){
							$TITLE=$THIS_ELEM.' |';
						}
						$HEADER = str_replace('{{_:'.$TAG.'}}',$TITLE,$HEADER);
					break;
					case 'Header' :
						$VIEW_HEADER = $this->viewRender('head',$THIS_ELEM,$page,$ext,$template);
					break;
					case 'Footer' :
						$VIEW_FOOTER = $this->viewRender('foot',$THIS_ELEM,$page,$ext,$template);
					break;
					default :
						$HEADER = str_replace('{{_:'.$TAG.'}}',$THIS_ELEM,$HEADER);
	                    $FOOTER = str_replace('{{_:'.$TAG.'}}',$THIS_ELEM,$FOOTER);
					break;
				}
				$VIEW_HEADER = str_replace('{{_:'.$TAG.'}}',$THIS_ELEM,$VIEW_HEADER);
				$VIEW_FOOTER = str_replace('{{_:'.$TAG.'}}',$THIS_ELEM,$VIEW_FOOTER);
			}
			$CONTENT = $this->Meta($CONTENT,'clear');
			$HTML = $HEADER.$VIEW_HEADER.$CONTENT.$VIEW_FOOTER.$FOOTER;
	      foreach($GLOBAL_META as $ELEM){
	        $HTML = str_replace('{{_:'.$ELEM.'}}','',$HTML);
	    }
	    $hook = explode('__',$page);
	    $hook_path = $this->hook_path($hook[0],$hook_data);

	    if(($hook_path=='')&&($this->chk_exists('content/hooks/'.$hook_data['sub-page'])=='true')){
	      $hook_path = 'content/hooks/'.$hook_data['sub-page'];
	    };

	    if($GLOBAL_404=='404'){
				if($cache_status=='1'){
					rmdir(str_replace('index.html','',$cache_path));
				}
			}else if($this->chk_404($page,$page_ext)=='true'){
				$this->mk_CACHED($page,$HTML);
			}else if($this->chk_hook($hook_path)=='true'){
			  $this->mk_CACHED($page,$HTML);
			}
			return $HTML;
	}

    // -- Render VIEW Element -- //
    private function viewRender($type,$elem,$page,$ext,$template){
        if($elem!=''){
            $VIEW = $this->getView($type,$elem,$ext,$template);
        }else {
            $VIEW = $this->getView($type,$page,$ext,$template);
        }
        return $VIEW;
    }

	// -- Cached Request (If Chache Exists)		------- //
	private function CACHE($page,$ext,$page_ext,$template,$hook_data){
      $cache_dir = 'core/cache/';
      $cache_page = $this->cache_STRUCT($page,$cache_dir);
      $cache_path = ($cache_dir.$cache_page.'.html');
		if($this->chk_exists($cache_path)=='true'){
			return $this->getContents($cache_path,$ext);
		}else {
			return $this->BUILD($page,$ext,$page_ext,$template,$hook_data,$cache_path,'1');
		}
	}

	// -- Create Cache of Latest Request		------- //
	private function mk_CACHED($page,$HTML){
        $cache_dir = 'core/cache/';
        $page = $this->cache_STRUCT($page,$cache_dir);
        $path = ($cache_dir.$page.'.html');
		if ($this->chk_exists($path)=='true'){}
		else {
			fopen($path, 'w');
		}
		file_put_contents($path,$HTML);
	}

    // -- Cache Structure:          ------- //
    private function cache_STRUCT($page,$cache_dir){
        $dir_path = explode('__',$page);
        $dir_count = count($dir_path);

        if( ($dir_count=='1'&&$dir_path[0]=='home')     ||
            ($dir_count=='1'&&$dir_path=='index.php')   ||
            ($dir_count=='1'&&$dir_path=='index.html') )
        {
            $page = 'index';
        }else if($dir_count=='1'&&$dir_path[0]!='home'){
            $page = $dir_path[0].'/index';
            if($this->chk_exists($cache_dir.$dir_path[0])=='true'){}
            else {
                mkdir($cache_dir.$dir_path[0],0777);
            }
        }else if($dir_count=='2'){
            $page = $dir_path[0].'/'.$dir_path[1].'/index';
            if($this->chk_exists($cache_dir.$dir_path[0])=='true'){}
            else {
                mkdir($cache_dir.$dir_path[0],0777);
            }
            if($this->chk_exists($cache_dir.$dir_path[0].'/'.$dir_path[1])=='true'){}
            else {
                mkdir($cache_dir.$dir_path[0].'/'.$dir_path[1],0777);
            }
        }
        return $page;
    }

	// -- Get Header File:			------- //
	private function getHeader($ext,$template){
		$HTML = $this->getContents('template/'.$template.'/header.'.$ext,$ext);
		return $HTML;
	}

	// -- Get View File:			------- //
	private function getView($view,$page,$ext,$template){
		$PAGE = explode('__',$page);
		$path = ('template/'.$template.'/views/'.$PAGE[0]);
		switch($view){
			case 'head' :
				$path = $path.'-header.'.$ext;
			break;
			case 'foot' :
				$path = $path.'-footer.'.$ext;
			break;
		}
		if($this->chk_exists($path)=='true'){
			return $this->getContents($path,$ext);
		}else {
			return '';
		}
	}

	// -- Get Page File:			------- //
	private function getPage($page,$ext,$template,$hook_data){


        if($page!='404-get'){
            $page_path = ('content/pages/'.$page.'.'.$ext);

            $hook = explode('__',$page);
            $hook_path = $this->hook_path($hook[0],$hook_data);

            if(($hook_path=='')&&($this->chk_exists('content/hooks/'.$hook_data['sub-page'])=='true')){
                $hook_path = 'content/hooks/'.$hook_data['sub-page'];
            };

            if($this->chk_exists($page_path)=='true'){
                return $this->getContents($page_path,$ext);
            }else if($this->chk_exists($hook_path)=='true'){
                return $this->getContents($hook_path,'php');
            }else {
                return $this->getContents('content/pages/404.'.$ext,$ext);
            }
        }else {
            return $this->getContents('content/pages/404.'.$ext,$ext);
        }
		return $HTML;
	}

	// -- Get Footer File:			------- //
	private function getFooter($ext,$template){
		$HTML = $this->getContents('template/'.$template.'/footer.'.$ext,$ext);
		return $HTML;
	}

	// -- Return File Variable Data:			------- //
	private function Meta($string,$action){
		$SYS_TAG = '/<%iio>(.*?)<%iio>/s';
		switch($action){
			case 'get' :
				$content = preg_match($SYS_TAG,$string,$matches);
                if($content!=''){
				    return $matches[0];
                }
			break;
			case 'clear' :
				$content = preg_replace($SYS_TAG,'',$string);
				return $content;
			break;
		}
	}
    private function globalMeta($template){
        $path = ('template/'.$template.'/config.txt');
        $template_config = $this->getContents($path,'txt');
        $template_meta = $this->Meta($template_config,'get');
        $meta = explode('@@',$template_meta);
        $tag_arr;
        foreach($meta as $elem){
            $this_tag = explode(';->',$elem);
            $tag_arr[] = $this_tag[0];
        }
        return $tag_arr;
    }

	// -- File/Meta Checks:			------ //
	private function chk_404($page,$ext){
		$path = ('content/pages/'.$page.'.'.$ext);
		if($this->chk_exists($path)=='true'){
			return 'true';
		}else {
			return 'false';
		}
	}
    private function chk_hook($path){
		if($this->chk_exists($path)=='true'){
			return 'true';
		}else {
			return 'false';
		}
	}
	private function chk_exists($path){
		if (file_exists($path)) {
			return 'true';
		} else {
			return 'false';
		}
    }
    private function check404_tag($META){
        $this_meta = explode(';->',$META[1]);
        if(($this_meta[0]=='Status')&&($this_meta[1]==404)){
            return '404';
        }else if($this_meta[0]==''){
            return '404';
        }else {
            return 'good';
        }
    }



    // -- Return HOOK Path:             ------- //
    private function hook_path($page,$hooks){
        $hook_count = $hooks['count'];
        for($i=0;$i<$hook_count;$i++){
            if(array_key_exists($page,$hooks['data'][$i])){
                $controller = $hooks['data'][$i][$page]['controller'];
                return 'content/hooks/'.$controller.'.php';
            }
        }
    }

	// -- File Open Request:			------ //
	private function getContents($url,$ext){
        switch($ext){
            case 'html';
            case 'txt' : $output = $this->static_content($url);
            break;
            case 'inc' ;
            case 'php' : $output = $this->dynamic_content($url);
            break;
            //
            default    : $output = $this->static_content($url);
            break;
        }
        return $output;
	}

    private function static_content($url){
        return file_get_contents($url);
    }

    private function dynamic_content($url){
        ob_start();
        $return = include $url;
        $output = ob_get_clean();
        ob_flush();
        return $output;
    }
}

/* 	----------------------------------------------------------- */
/* 	----------------------------------------------------------- */
?>
