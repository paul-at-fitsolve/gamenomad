<?php

/**
 * The GameNomad home page
 * @author wjgilmore
 *
 */
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
      if ($this->_helper->FlashMessenger->hasMessages()) {
         $this->view->messages = $this->_helper->FlashMessenger->getMessages();
      } 
      $this->em = $this->_helper->EntityManager();
    }

    public function indexAction()
    {

      // Retrieve a list of hot platform-specific games
      $this->view->hotGames = $this->em->getRepository('Entities\Rank')
                                   ->hot(1,10);

      $this->view->recentGames = $this->em->getRepository('Entities\Game')->recentGames(10);                                  
                                 
    }


}