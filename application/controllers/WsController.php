<?php

class WsController extends Zend_Controller_Action
{

  public function init()
  {
      $this->em = $this->_helper->EntityManager();
      $this->_helper->layout()->disableLayout();
      Zend_Controller_Front::getInstance()
        ->setParam('noViewRenderer', true);
  }

  public function usernameAction()
  {
  
    // Retrieve the provided username
    $username = $this->_request->getParam('username');
    
    // Does an account associated with this username already exist?
    $account = $this->em->getRepository('Entities\Account')
                    ->findOneByUsername($username);
  
    // If $account is null, the username is available
    if (is_null($account))
    {
      echo json_encode("TRUE");  
    } else {
      echo json_encode("FALSE");
    }
                  
  }

}