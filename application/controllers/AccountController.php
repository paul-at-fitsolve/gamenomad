<?php

require_once "GoogleMap.php";

/**
 * 
 *
 */
class AccountController extends Zend_Controller_Action
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
     */
    public function indexAction()
    {
      // Make sure the user is logged-in
      $this->_helper->LoginRequired();
    }

    /**
     * 
     *
     *
     */
    public function loginAction()
    {

      $this->view->pageTitle = 'GameNomad: Login to Your Account';

      $form = new Application_Model_FormLogin();
            
      // Has the login form been posted?
      if ($this->getRequest()->isPost()) {

        // If the submitted data is valid, attempt to authenticate the user
        if ($form->isValid($this->_request->getPost())) {

          // Did the user successfully login?
          if ($this->_authenticate($this->_request->getPost())) {

            $account = $this->em->getRepository('Entities\Account')
                            ->findOneByEmail($form->getValue('email'));

            // Save the account to the database
            $this->em->persist($account);
            $this->em->flush();

            // Generate the flash message and redirect the user
            $this->_helper->flashMessenger->addMessage(
              Zend_Registry::get('config')->messages->login->successful
            );

            return $this->_helper->redirector('index', 'index');

          } else {
            $this->view->errors["form"] = array(
              Zend_Registry::get('config')->messages->login->failed
            );
          }

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $this->view->form = $form;

    }

    /**
     * Create a new GameNomad account
     *
     *
     */
    public function registerAction()
    {

      // Instantiate the registration form model
      $form = new Application_Model_FormRegister();

      // Has the form been submitted?
      if ($this->getRequest()->isPost()) {

        // If the form data is valid, process it
        if ($form->isValid($this->_request->getPost())) {
		
          // Does an account associated with this username already exist?
          $account = $this->em->getRepository('Entities\Account')
                          ->findOneByUsernameOrEmail($form->getValue('username'), $form->getValue('email'));          
          
          if (! $account)
          {
  
  	        $account = new \Entities\Account;
  
            // Assign the account attributes
            $account->setUsername($form->getValue('username'));
            $account->setEmail($form->getValue('email'));
            $account->setPassword($form->getValue('password'));
            $account->setZip($form->getValue('zip'));
  
            // Geocode the user's zip code
            $map = new GoogleMapAPI();
            $coordinates = $map->getGeoCode($form->getValue('zip'));
  
            // Set the coordinates
            $account->setLatitude($coordinates['lat']);
            $account->setLongitude($coordinates['lon']);
  
            $account->setConfirmed(0);
  
            // Set the confirmation key
  	        $account->setRecovery($this->_helper->generateID(32));

            try {

              // Save the account to the database
              $this->em->persist($account);
              $this->em->flush();
  	        	        		    		
              // Create a new mail object
              $mail = new Zend_Mail();
  		          	
              // Set the e-mail from address, to address, and subject
              $mail->setFrom(Zend_Registry::get('config')->email->support);
              $mail->addTo($account->getEmail(), "{$account->getUsername()}");
              $mail->setSubject('GameNomad.com: Confirm Your Account');
  			          
              // Retrieve the e-mail message text
              include "_email_confirm_email_address.phtml";
  			          
              // Set the e-mail message text
              $mail->setBodyText($email);
  
              // Send the e-mail
              $mail->send();
  
              // Set the flash message
              $this->_helper->flashMessenger->addMessage(
                Zend_Registry::get('config')->messages->register->successful
              );
  
              // Redirect the user to the home page
              $this->_helper->redirector('login', 'account');
  
            } catch(Exception $e) {
              $this->view->errors = array(array("There was a problem creating your account."));
            }  	        
  	        
          } else {
            
            $this->view->errors = array(
              array("The desired username {$form->getValue('username')} has already been taken, or
              the provided e-mail address is already associated with a registered user.")
            );
            
          }

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $this->view->form = $form;

    }

    /**
     * Confirms a user's e-mail address
     *
     *
     */
    public function confirmAction()
    {

      $key = $this->_request->getParam('key');

      // Key should not be blank
      if ($key != "")
      {

        $em = $this->getInvokeArg('bootstrap')->getResource('entityManager');

        $account = $em->getRepository('Entities\Account')
                      ->findOneByRecovery($this->_request->getParam('key'));

        // Was the account found?
        if ($account) {

          // Account found, so confirm it and reset the recovery attribute
          $account->setConfirmed(1);
          $account->setRecovery("");

          // Save the account to the database
          $em->persist($account);
          $em->flush();
        	
          // Set the flash message and redirect the user to the login page
          $this->_helper->flashMessenger->addMessage(
            Zend_Registry::get('config')->messages->register->confirm->successful
          );
          $this->_helper->redirector('login', 'account');
        	
        } else {

          // Set the flash message and redirect the user to the login page
          $this->_helper->flashMessenger->addMessage(
            Zend_Registry::get('config')->messages->register->confirm->failed
          );
          $this->_helper->redirector('login', 'account');

        }

      }
        
    }

    /**
     * Change the user password
     *
     *
     */
    public function passwordAction()
    {

      // Make sure the user is logged-in
      $this->_helper->LoginRequired();

      $form = new Application_Model_FormPassword();

      if ($this->getRequest()->isPost()) {
	
        if ($form->isValid($this->_request->getPost())) {
		
          $em = $this->_helper->EntityManager();

          // Set the account password
          $this->view->account->setPassword($form->getValue('pswd'));

          // Save the account to the database
          $em->persist($this->view->account);
          $em->flush();

          $this->_helper->flashMessenger->addMessage('Your password has been updated!');
          $this->_helper->redirector('index', 'account');

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $this->view->form = $form;

    }

    /**
     * Manage the user profile
     *
     *
     */
    public function profileAction()
    {

      // Make sure the user is logged-in
      $this->_helper->LoginRequired();

      $em = $this->_helper->EntityManager();

      $form = new Application_Model_FormProfile();

      if ($this->getRequest()->isPost()) {
	
        if ($form->isValid($this->_request->getPost())) {

          $account = $em->getRepository('Entities\Account')
                        ->findOneByEmail($this->view->account->getEmail());

          $account->setUsername($form->getValue('username'));
          $account->setEmail($form->getValue('email'));
          $account->setZip($form->getValue('zip'));

          $map = new GoogleMapAPI();
          $coordinates = $map->getGeoCode($form->getValue('zip'));

          $account->setLatitude($coordinates['lat']);
          $account->setLongitude($coordinates['lon']);

          $em->persist($account);
          $em->flush();

          $this->_helper->flashMessenger->addMessage('Your profile has been updated!');
          $this->_helper->redirector('index', 'account');

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $account = $em->getRepository('Entities\Account')
                    ->findOneByEmail($this->view->account->getEmail());

      $data = array(
                'username' => $account->getUsername(),
                'email' => $account->getEmail(),
                'zip' => $account->getZip()
              );

      $form->setDefaults($data);

      $this->view->form = $form;

    }

  /**
   *
   *
   *
   *
   */
    public function publicAction()
    {

      $em = $this->_helper->EntityManager();

      $username = $this->_request->getParam('username');

      $this->view->publicProfile = $em->getRepository('Entities\Account')
                 ->findOneByUsername($username);

      if (! $this->view->publicProfile)
      {
          $this->_helper->flashMessenger->addMessage('User account profile not found!');
          $this->_helper->redirector('index', 'index');
      }

    }

	/**
	 * Logout of the system
	 * Logs the user out of the website
	 *
	 */
	public function logoutAction()
	{		
      // Make sure the user is logged-in
      $this->_helper->LoginRequired();
  
      Zend_Auth::getInstance()->clearIdentity();
      $this->_helper->flashMessenger->addMessage('You are logged out of your account');
      $this->_helper->redirector('index', 'index');
      return;
	}	   

  /**
   *
   *
   *
   *
   */
  public function gamesAction()
  {

    // Make sure the user is logged-in
    $this->_helper->LoginRequired();

    // Get account's game library
    $this->view->games = $this->view->account->getGames();

  }

  /**
   * View list of friends
   *
   *
   *
   */
  public function friendsAction()
  {

      // Make sure the user is logged-in
      $this->_helper->LoginRequired();

    // Get users this account is following
    $this->view->friends = $this->view->account->getFriends();      

  }

  /**
   * Initiate the password recovery process
   *
   *
   *
   */
  public function lostAction()
  {

      $form = new Application_Model_FormLost();

      if ($this->getRequest()->isPost()) {
	
        // If form is valid, make sure the e-mail address is associated
        // with an account
        if ($form->isValid($this->_request->getPost())) {
			          	
          $account = $this->em->getRepository('Entities\Account')
                    ->findOneByEmail($form->getValue('email'));          

          // If account is found, generate recovery key and mail it to
          // the user
          if ($account)
          {

            // Generate a random password
            $account->setRecovery($this->_helper->generateID(32));

            $this->em->persist($account);
            $this->em->flush();

            // Create a new mail object
            $mail = new Zend_Mail();

            // Set the e-mail from address, to address, and subject
	        $mail->setFrom(Zend_Registry::get('config')->email->support);
	        $mail->addTo($form->getValue('email'));
	        $mail->setSubject("GameNomad: Generate a new password");
		            
            // Retrieve the e-mail message text
	        include "_email_lost_password.phtml";
		            
            // Set the e-mail message text
	        $mail->setBodyText($email);

            // Send the e-mail
	        $mail->send();

            $this->_helper->flashMessenger->addMessage('Check your e-mail for further instructions');
            $this->_helper->redirector('login', 'account');

          }

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $this->view->form = $form;

  }

  /**
   * Complete the password recovery process
   *
   *
   *
   */
  public function recoverAction()
  {

    $key = $this->_request->getParam('key');

    if ($key != "")
    {

      $account = $this->em->getRepository('Entities\Account')
                ->findOneByRecovery($key);        
      
      // If account is found, generate recovery key and mail it to
      // the user
      if ($account)
      {

        // Generate a random password
        $password = $this->_helper->generateID(8);
        $account->setPassword($password);

        // Erase the recovery key
        $account->setRecovery("");    

        // Save the account
        $this->em->persist($account);
        $this->em->flush();

        // Create a new mail object
        $mail = new Zend_Mail();

        // Set the e-mail from address, to address, and subject
        $mail->setFrom(Zend_Registry::get('config')->email->support);
        $mail->addTo($account->getEmail());
        $mail->setSubject("GameNomad: Your password has been reset");
            
        // Retrieve the e-mail message text
        include "_email_recover_password.phtml";
            
        // Set the e-mail message text
        $mail->setBodyText($email);

        // Send the e-mail
        $mail->send();

        $this->_helper->flashMessenger->addMessage(
          Zend_Registry::get('config')->messages->account->password->reset
        );
        $this->_helper->redirector('login', 'account');

      }

    }

    // Either a blank key or non-existent key was provided
    $this->_helper->flashMessenger->addMessage(
      Zend_Registry::get('config')->messages->account->password->nokey
    );
    $this->_helper->redirector('login', 'account');

  }

  /**
   * Invite a friend to join GameNomad
   *
   *
   *
   */
  public function inviteAction()
  {
      // Make sure the user is logged-in
      $this->_helper->LoginRequired();

      $form = new Application_Model_FormInvite();

      if ($this->getRequest()->isPost()) {
	
        if ($form->isValid($this->_request->getPost())) {
		
          // Create a new mail object
          $mail = new Zend_Mail();
	          	
          $inviterName = $this->_request->getPost('username');

          // Set the e-mail from address, to address, and subject
	      $mail->setFrom(Zend_Registry::get('config')->email->support);
	      $mail->addTo($this->_request->getPost('email'));
	      $mail->setSubject("$inviterName has invited you to join GameNomad");
		          
          // Retrieve the e-mail message text
	      include "_email_invitation.phtml";
		          
          // Set the e-mail message text
	      $mail->setBodyText($email);

          // Send the e-mail
	      $mail->send();

          $this->_helper->flashMessenger->addMessage('Your invitation has been sent!');
          $this->_helper->redirector('invite', 'account');

        } else {
          $this->view->errors = $form->getErrors();
        }

      }

      $this->view->form = $form;

  }

  public function associateAction()
  {
    // We don't want a view or layout to display
    $this->_helper->layout()->disableLayout();
    Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);   

    // Friend this user
    if ($this->getRequest()->getParam('do') == "follow")
    {
      
      // What user is being followed?
      $username = $this->getRequest()->getParam('username');        
      
      // Retrieve the game info
      $account = $this->em->getRepository('Entities\Account')
                      ->findOneByUsername($username);      
      
      $this->view->account->addFriend($account);
      $this->em->persist($this->view->account);
      $this->em->flush();      
      
      $this->_helper->flashMessenger->addMessage(
        "You are now following this gamer!"
      );
      
      $this->_helper->redirector->gotoSimple('public', 'account', null, array('username' => $username));
      
    // Unfriend this user
    } else if (($this->getRequest()->getParam('do') == "unfollow")) {
      
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
      
      $this->_helper->redirector->gotoSimple('public', 'account', null, array('username' => $username));                               
                                    
    }
     
    
  }  
  
  /**
   * 
   *
   *
   */
  protected function _authenticate($data)
  {


    $db = Zend_Db_Table::getDefaultAdapter();
    $authAdapter = new Zend_Auth_Adapter_DbTable($db);

    $authAdapter->setTableName('accounts');
    $authAdapter->setIdentityColumn('email');
    $authAdapter->setCredentialColumn('password');
    $authAdapter->setCredentialTreatment('MD5(?) and confirmed = 1');

    $authAdapter->setIdentity($data['email']);
    $authAdapter->setCredential($data['pswd']);

    $auth = Zend_Auth::getInstance();
    $result = $auth->authenticate($authAdapter);

    if ($result->isValid())
    {

      if ($data['public'] == "1") {
        Zend_Session::rememberMe(1209600);
      } else {
        Zend_Session::forgetMe();	
      }

      return TRUE;

    } else {

      return FALSE;

    }


  }


}

