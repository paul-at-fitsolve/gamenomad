<?php

/**
 * The games controller
 *
 * Games controller description
 *
 * @version
 * @author W. Jason Gilmore
 * @project 
 *
 */
class GamesController extends Zend_Controller_Action
{

    public function init()
    {
      $this->em = $this->_helper->EntityManager();
      
      if ($this->_helper->FlashMessenger->hasMessages()) {
         $this->view->messages = $this->_helper->FlashMessenger->getMessages();
      }       
      
    }

  /**
   * Build the GameNomad game compilation
   *
   *
   *
   */
  public function indexAction()
  {

  }

  /**
   * View a video game according to its ASIN
   *
   *
   *
   */
  public function viewAction()
  {

    // What game are we looking at?
    $asin = $this->getRequest()->getParam('asin');    
        
    // Retrieve the Amazon Affiliate associate ID
    $this->view->associateID = Zend_Registry::get('config')->amazon->associate_id;
    
    // Retrieve the game info
    $this->view->game = $this->em->getRepository('Entities\Game')
                             ->findOneByAsin($asin);                             
                             
    // Retrieve a list of hot platform-specific games
    $this->view->hotGames = $this->em->getRepository('Entities\Rank')
                                 ->hot($this->view->game->getPlatform()->getId(),10);
                                 
  }

  /**
   * Add or remove a game from a user's library 
   * 
   */
  public function associateAction()
  {
    $this->_helper->layout()->disableLayout();
    Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);   

    // Add game to the user's library
    if ($this->getRequest()->getParam('do') == "add")
    {
      
      // What game are we looking at?
      $asin = $this->getRequest()->getParam('asin');        
      
      // Retrieve the game info
      $game = $this->em->getRepository('Entities\Game')
                               ->findOneByAsin($asin);      
      
      $this->view->account->addGame($game);
      $this->em->persist($this->view->account);
      $this->em->flush();      
      
      $this->_helper->flashMessenger->addMessage(
        "Added game to your library!"
      );
      
      $this->_helper->redirector($asin, 'games');
      
    // Remove game from user's library
    } else if (($this->getRequest()->getParam('do') == "remove")) {
      
      // What game are we looking at?
      $asin = $this->getRequest()->getParam('asin');        
      
      // Retrieve the game info
      $game = $this->em->getRepository('Entities\Game')
                               ->findOneByAsin($asin);

      $this->view->account->removeGame($game);                               
                               
      $this->em->persist($this->view->account);
      $this->em->flush();                                 
                               
      $this->_helper->flashMessenger->addMessage(
        "Removed game from your library!"
      );
      
      $this->_helper->redirector($asin, 'games');                               
                                    
    }
     
    
  }
  
  /**
   * View all video games associated with a platform
   *
   *
   *
   */
  public function platformAction()
  {

    // What platform are we browsing?
    $platform = $this->getRequest()->getParam('platform');
    
    // How many games do we want to display per page?
    $gamesPerPage = Zend_Registry::get('config')->pagination->games->per->page;
    
    // What page are we viewing?
    $page = $this->getRequest()->getParam('page');
    
    $this->view->page = $page != "" ? $page : 0;
    
    // Retrieve the platform object
    $this->view->platform = $this->em->getRepository('Entities\Platform')
                                 ->findOneByAbbreviation($platform);
    
    // How many games is GameNomad tracking for this platform?
    $this->view->count = $this->em->getRepository('Entities\Platform')
                                 ->countGames($this->view->platform->getId());

    // How many total pages? This is needed for the pagination navigation menu
    $this->view->pages = ceil($this->view->count / $gamesPerPage);
    
    // Retrieve paginated list of games
    $this->view->games = $this->em->getRepository('Entities\Platform')
                              ->paginateGames(
                                $this->view->platform->getId(), 
                                (int)$this->view->page,
                                (int)$gamesPerPage
                              );
    
    // Retrieve a list of hot platform-specific games
    $this->view->hotGames = $this->em->getRepository('Entities\Rank')
                                 ->hot($this->view->platform->getId(),10);
      
  }

  /**
   * View the hottest video games according to the latest Amazon sales ranks
   *
   *
   *
   */
  public function hotAction()
  {

    // Retrieve the platform object
    $this->view->platform = $this->em->getRepository('Entities\Platform')
                                 ->findOneByAbbreviation($this->getRequest()->getParam('platform'));

    $this->view->games = $this->em->getRepository('Entities\Rank')->hot($this->view->platform->getId(),20);
                                       

  }

}





