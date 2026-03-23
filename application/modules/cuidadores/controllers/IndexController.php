<?php
class Cuidadores_IndexController extends Zend_Controller_Action {
    
    
    public function verAction() {
        $this->view->slug = $this->getRequest()->getParam('slug');
    }
    
    public function buscarAction() {
        $this->view->especie = $this->getRequest()->getParam('especie');
        $this->view->ciudad  = $this->getRequest()->getParam('ciudad');
        $this->view->desde   = $this->getRequest()->getParam('desde');
    }
    
}