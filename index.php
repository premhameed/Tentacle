<?php
error_reporting(E_STRICT|E_ALL);

// Application configuration
//----------------------------------------------------------------------------------------------

require_once('system-variables.php');

if (!file_exists('application/config/'.CONFIGURATION.'/db.php')):
   header( 'Location: '.BASE_URI.'/setup/' );
   exit();
endif;



// End of configuration
//----------------------------------------------------------------------------------------------
define('DINGO',1);
require_once(SYSTEM.'/dingo.php');
bootstrap::run();