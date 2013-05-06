<?php

class WJG_Controller_Action_Helper_Initializer extends Zend_Controller_Action_Helper_Abstract
{
	
	public function init()
	{     		
		
      // Initialize the errors array
      Zend_Layout::getMvcInstance()->getView()->errors = array();
    
      // Is the user logged in?
      $auth = Zend_Auth::getInstance();
    
      $em = $this->getActionController()->getInvokeArg('bootstrap')->getResource('entityManager');    
      
      if ($auth->hasIdentity()) {
    
        $identity = $auth->getIdentity();
    
        if (isset($identity)) {    	
    
          // Retrieve information about the logged-in user
          $account = $em->getRepository('Entities\Account')->findOneByEmail($identity);
    
          Zend_Layout::getMvcInstance()->getView()->account = $account;			    	
    
        }
    
      }
    
      $query = $em->createQuery(
          'SELECT COUNT(g.id) FROM Entities\Game g'
        );
        
      $gameCount = $query->getSingleScalarResult();    
  
      Zend_Layout::getMvcInstance()->getView()->gameCount = $gameCount;

	}    	
		
}
