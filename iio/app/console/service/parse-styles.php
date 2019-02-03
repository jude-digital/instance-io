<?php

$config = file_get_contents('../../config/app.json');

// --
// -- @@ Components Controller :
require_once('../controller/Component/components.php');

// --
// -- @@ Style Parser :
require_once('service/style/parser.php');

// -- Run the Style Parser :
new PARSE_STYLES($config);

//// ------------ ////
