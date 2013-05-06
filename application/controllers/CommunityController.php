<?php

/**
 *
 *
 *
 *
 */
class CommunityController extends Zend_Controller_Action
{

  public function init()
  {
    $this->em = $this->_helper->EntityManager();
    
    if ($this->_helper->FlashMessenger->hasMessages()) {
       $this->view->messages = $this->_helper->FlashMessenger->getMessages();
    }       
    
  }  
  
  /**
   *
   *
   *
   *
   */
  public function indexAction()
  {

    // Make sure the user is logged-in
    $this->_helper->LoginRequired();

    // Retrieve the nearby accounts
    $this->view->accounts = $this->em->getRepository('Entities\Account')
                             ->getNearbyAccounts($this->view->account, 10);
                         
  }

  /**
   *
   *
   *
   *
   */
  public function gamersAction()
  {

  }

  /**
   * Search for friends
   *
   *
   *
   */
  public function searchAction()
  {

  }

}

