 <?php
	ob_start();
	//session_start();
	ini_set( "display_errors", 1);	
	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	set_time_limit(-1);
	ini_set('memory_limit', '1024M');

	if (!defined( "MAIN_INCLUDE_PATH" )) {
		define( "MAIN_INCLUDE_PATH", dirname(__FILE__)."/");
	}

	$data					=	$_POST;
	$request				=	$_REQUEST;
	$get_data    			= 	$_GET;

	require_once('config.php');
	define("ABSOLUTE_PATH",str_replace("includes/","",MAIN_INCLUDE_PATH));	
	define("MAIN_CLASS_PATH", MAIN_INCLUDE_PATH."Classes/");
	define("MAIN_COMMON_PATH", MAIN_CLASS_PATH."Common/");
	define("SITEGLOBALPATH", $global_config["SiteGlobalPath"]);
	define("SITELOCALPATH", $global_config["SiteLocalPath"]);

	require_once(MAIN_COMMON_PATH."class.Common.php");
	$objCommon = new CommonClass();
	
	require_once(MAIN_CLASS_PATH."class.Event.php");
	$objEvent = new Event();		// Crete Object

	require_once(MAIN_CLASS_PATH."class.Event_type.php");
	$objEvent_type = new Event_type();		// Crete Object
	
 	require_once("Tables.Config.php");
	require_once("functions.php");
?>
