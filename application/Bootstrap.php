<?php

use Firebase\JWT\JWT;
use Zend_Application_Bootstrap_Bootstrap as ZendBootstrap;
use Zend_Controller_Action_HelperBroker as ZendHelperBroker;
use Zend_Controller_Action_Helper_ViewRenderer as HelperViewRenderer;

class Bootstrap extends ZendBootstrap
{
    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();

        // Set path to /library/app/view/helpers with prefix 'App_View_Helper'
        $view->addHelperPath(realpath(APPLICATION_PATH . '/../library/app/view/helpers'), 'App_View_Helper');

        /** @var HelperViewRenderer */
        $viewRenderer = ZendHelperBroker::getStaticHelper('ViewRenderer');

        // Add the view to the ViewRenderer
        $viewRenderer->setView($view);
        
        // Return the view to store it by the bootstrap
        return $view;
    }

    protected function _initLanguage()
    {
        // Inicializa el lenguaje aqu� si es necesario
    }
    
    /*
     * Para utilizar las variables de php.ini por ejemplo xtoken
     */
    protected function _initConfig()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        Zend_Registry::set('config', $config);
        return $config;
    }
    
    

protected function _initLayout()
{
    Zend_Layout::startMvc(array(
        'layoutPath' => APPLICATION_PATH . '/layouts/scripts'
    ));
}

    private function checkToken($layout)
    {}
} // fin de la clase
