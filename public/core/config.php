<?php

/*	================================================== //
 * 
 * 		----
 * 
 * 		INSTANCE.IO {} --
 * 		Alonzi -
 * 
 *		----
 *
 *      @@CONFIGURATION FILE    :
 *
 *          //  This is the main instance.IO
 *          //  file which contains the config
 *          //  array.
 * 
 * 	================================================== // 	
 */

/*  -----------------------

    @@CACHE :
    
        -- This determines whether Instance.IO
        -- should serve the cached HTML file (if exists) 
        -- or compile the page.
        
        -- 0 = Off
        -- 1 = On
        
        *Default: 0

//  ----------------------- */
$CACHE = '0';


/*  -----------------------

    @@TEMPLATE EXTENSION :
    
        -- Set the template file extension that
        -- is used. This allows for dynamic template
        -- parts to be rendered if needed.
        
        -- NOTE: This extension does not need to be
        -------- the same as the page extension.
        
        *Default: html
        *Options: php,txt,inc,html

//  ----------------------- */
$EXT = 'html';

/*  -----------------------

    @@PAGE EXTENSION :
    
        -- Set the pages file extension that
        -- is used. This allows for dynamic pages
        -- to be rendered if needed.
        
        -- NOTE: This extension does not need to be
        -------- the same as the template extension.
        
        *Default: html
        *Options: php,txt,inc,html

//  ----------------------- */
$PAGE_EXT = 'html';


/*  -----------------------

    @@TEMPLATE:
    
        -- Determine the template folder that is
        -- to be used. The template must be formatted
        -- to work with Instance.IO. 
        
        -- NOTE: Settings above must reflect the
        -------- filesystem/extensions used in the
        -------- template,
        
        *Default: default

//  ----------------------- */
$TEMPLATE = 'default';

/*  -----------------------

    @@HOOKS:
    
        -- Define all hooks to be used on
        -- specific pages and/or sub-pages
        -- within the template.
        
        -- EXAMPLE: $HOOKS[] = ADD_HOOK('blog','blog-controller');
        
        -- DELETE: $HOOKS = ''; Unless No Hooks Available;
        
        *Default: [empty]

//  ----------------------- */
$HOOKS = '';
//$HOOKS[] = ADD_HOOK('','');

/*  -----------------------

    @@SUB-HOOKS:
    
        -- Determine the sub-hook controller for
        -- all general sub-pages (if page/hook does
        -- not exist in array.
        
        -- EXAMPLE: $SUB_HOOK = 'sub.php';
        
        *Default: null

//  ----------------------- */
$SUB_HOOK = 'null';

//  ----------------------- */

///////////////////////////////////////////////////////////////// 

/*  -----------------------

    @@HOOK (BUILD) :
    
        -- The following is a helper function which
        -- allows for rapid hook development. You
        -- may add to the config array if absolutely
        -- needed.
        
        -- WARNING: You do not need to edit
        -- anything below this line. Edit at
        -- your own risk.
        
//  ----------------------- */
function ADD_HOOK($name,$controller){
    return array(
            $name => array( 
                'controller'    =>  $controller
            )
    );
}
if($HOOKS!=''){     $HOOK_COUNT = count($HOOKS); 
}else {             $HOOK_COUNT = 0; 
}



/*  -----------------------

    @@CONFIG (BUILD) :
    
        -- The following builds the final
        -- configuration JSON array to be
        -- used for the system. 
        
        -- WARNING: You do not need to edit
        -- anything below this line. Edit at
        -- your own risk.
        
//  ----------------------- */
$CONFIG = json_encode(
    array(    
        'CACHE'     =>  $CACHE,
        'EXT'       =>  $EXT,
        'PAGE_EXT'  =>  $PAGE_EXT,
        'TEMPLATE'  =>  $TEMPLATE,
        'HOOKS'     =>  array(
                            'count' =>  $HOOK_COUNT,
                            'sub-page' => $SUB_HOOK,
                            'data'  =>  $HOOKS
        )
    )
);
$CONFIG = json_decode($CONFIG,true);


//  ----------------------- */

///////////////////////////////////////////////////////////////// 

?>