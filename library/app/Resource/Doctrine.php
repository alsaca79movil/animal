<?php

use Zend_Application_Resource_ResourceAbstract as ResourceAbstract;
use Doctrine\Common\Cache\ArrayCache as ArrayCache;
use Doctrine\Common\Cache\ApcCache as ApcCache;

/**
 * The Doctrine resource.
 *
 * @package App
 * @subpackage Resource
 */    
class App_Resource_Doctrine extends ResourceAbstract {
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Application_Resource_Resource::init()
	 * @return Doctrine\ORM\EntityManager
	 */
	public function init() {
		// load options of application.ini
		$options = $this -> getOptions();

		// Doctrine (use include_path)
		//$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
		$classLoader = new \Doctrine\Common\ClassLoader('Models', dirname(dirname(__FILE__)));
        $classLoader -> register();
        
        // Entities
        // The namespace of the classes to load.
    	// $includePath The base include path to use.
        $classLoader = new \Doctrine\Common\ClassLoader( 
        	$options['entitiesPathNamespace'],
            $options['entitiesPath']
        );
        $classLoader -> register();
        
        // Proxies
        // The namespace of the classes to load.
    	// $includePath The base include path to use.
        $classLoader = new \Doctrine\Common\ClassLoader(
            $options['proxiesPathNamespace'],
            $options['proxiesPath']
        );
        $classLoader -> register();
        
        // Repositories
        $classLoader = new \Doctrine\Common\ClassLoader(
            $options['repositoriesPathNamespace'],
            $options['repositoriesPath']
        );
        $classLoader -> register();
       
        
        $isDevelopment = (bool) preg_match('/(\w+_)?development/', APPLICATION_ENV);

        // Now configure doctrine
        if ($isDevelopment) {
            $cache = new ArrayCache();
        } else {
            $cache = new ApcCache();
        }
        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl($cache);
		
        // Get booststrap
//		$bootstrap = $this->getBootstrap();
//		$bootstrap->bootstrap('frontController');
//		$front = $bootstrap->getResource('frontController');
//		// loading path for modules
//		$pathModels = array();
//		foreach ($front->getControllerDirectory() as $module => $path) {
//			$pathModels[] = APPLICATION_PATH . "/modules/$module/models";
//		}
//		$pathModels[] = APPLICATION_PATH . "/models";

		// set path models for generate entities
		//$driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . "/../library/App/Models");
		$driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . "/../library/app/models");
		//$driverImpl = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(array(__DIR__."/models"));
		$driverImpl->getAllClassNames();//sirve para llamar todas las clases para relaciones 
        $config -> setMetadataDriverImpl($driverImpl);
        $config -> setQueryCacheImpl($cache);
        
        // load proxies
        $config -> setProxyDir($options['proxiesPath']);
		
        // load proxy namespaces
        $config -> setProxyNamespace($options['proxiesPathNamespace']);
        $config -> setAutoGenerateProxyClasses($isDevelopment);
        
        $em = \Doctrine\ORM\EntityManager::create(
            $this -> _buildConnectionOptions($options['connection']),
            $config
        );
        
        // Once we have the EntityManager ready, add it to the registry
        Zend_Registry::set('em', $em);
        
        // end
        return $em;
	}

	/**
	 * Metodo para construir las opciones de conexion para Doctrine.
	 * Estoy seguro que podemos buscar algo mas elegante para construir las opciones de 
	 * conexion. 
	 * Seguro que tu puedes refactorizar algo :)
	 *
	 * @param Array $options The options array defined on the application.ini file
	 * @return Array
	 */
	protected function _buildConnectionOptions(array $options)
	{
		$connectionSpec = array(
            'pdo_sqlite' => array('user', 'password', 'path', 'memory'),
            'pdo_mysql'  => array('user', 'password', 'host', 'port', 'dbname', 'unix_socket'),
            'pdo_pgsql'  => array('user', 'password', 'host', 'port', 'dbname'),
            'pdo_oci'    => array('user', 'password', 'host', 'port', 'dbname', 'charset')
		);

		$connection = array(
            'driver' => $options['driver']
		);

		foreach ($connectionSpec[$options['driver']] as $driverOption) {
			if (isset($options[$driverOption]) && !is_null($driverOption)) {
				$connection[$driverOption] = $options[$driverOption];
			}
		}

		if ( isset($options['driverOptions']) && !is_null($options['driverOptions']) ) {
			$connection['driverOptions'] = $options['driverOptions'];
		}

		return $connection;
	}
}