<?php

// -- @@ Get Global Configuration File :
if(!isset($configuration)){
  $configuration = file_get_contents('../iio/config/app.json');
}

// -- @@ Put Config JSON Into Global Variable :
define('CONFIG', $configuration );

// -- @@ Decode Global Config File :
$conf = json_decode(CONFIG,true);

// -- @@ Define InstanceIO Path :
define('IIO', $conf['sys-conf']['iio-path']);

// -- @@ Define Web Public Path :
define('PUBLIC', $conf['sys-conf']['public-path']);
