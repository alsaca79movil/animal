<?php
	use Doctrine\ORM\Tools\Console\ConsoleRunner;

	//Define root path
	if ( !defined('ROOT_PATH') )
		define('ROOT_PATH', dirname(__FILE__) );

	// Define path to application directory
	if ( !defined('APPLICATION_PATH') ) {
		define('APPLICATION_PATH', ROOT_PATH . '/application');
	}
		
	// Define application environment
	if ( !defined('APPLICATION_ENV')) {
		//definimos bajo que configuracion vamos a trabajar con el application.ini, por default production
		define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development') );
	}
	
	// Ensure library/ is on include_path
	set_include_path(implode(PATH_SEPARATOR, array(
    	realpath(APPLICATION_PATH . '/../library'),
    	get_include_path(),
	)));
	
	/** Zend_Application */
	require_once 'Zend/Application.php';
	
	// Create application, bootstrap, and run
	$application = new Zend_Application(
		APPLICATION_ENV, 
		APPLICATION_PATH . '/configs/application.ini'
	);
	$bootstrap = $application->getBootstrap();
	$bootstrap->bootstrap( 'doctrine' );
	
	return ConsoleRunner::createHelperSet($bootstrap->getResource('doctrine'));