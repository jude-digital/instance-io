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
 * 	================================================== // 	
 */

/*
 * 		GLOBAL CONFIGURATIONS 	------- //
 */
include('core/config.php');

/*
 * 		RENDER PAGE INSTANCE.IO	------- //
 */
include('core/controllers/Instance.IO.php');
echo new INSTANCE_IO($CONFIG);

?>