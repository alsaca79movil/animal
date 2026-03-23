<?php


class Default_IndexController extends Zend_Controller_Action
{   
    public function indexAction()
    { 
        $this->view->titulo = 'Encuentra el cuidador perfecto';
    }
}