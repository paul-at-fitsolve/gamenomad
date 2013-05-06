<?php

class AccountControllerTest extends ControllerTestCase
{
	
  /**
   * A reusable method which we can call before running any tests
   * which require an authenticated user
   *
   *
   */
  private function _loginValidUser()
  {

    $this->request->setMethod('POST')
         ->setPost(array(
         'email'  => 'wj@wjgilmore.com',
         'pswd'   => 'werlbwa3',
         'public' => 0
    ));

    $this->dispatch('/account/login');

    $this->assertRedirectTo('/account/friends');
    $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());

  }

  /**
   * A reusable method which we can call before running any tests
   * which require an invalid user
   *
   *
   */
  private function _loginInvalidUser()
  {

    $this->request->setMethod('POST')
         ->setPost(array(
             'email' => 'wj@wjgilmore.com',
             'pswd'  => 'invalidpassword',
             'public' => 0
           ));

    $this->dispatch('/account/login');

  }

  /**
   *
   *
   */
  public function testUsersCanRegisterWhenUsingValidData()
  {

    $this->request->setMethod('POST')
         ->setPost(array(
             'username'         => 'jasong123',
             'zip_code'         => '43215',
             'email'            => 'jason1@wjgilmore.com',
             'password'         => 'secret',
             'confirm_pswd'     => 'secret',
           ));

    $this->dispatch('/account/register');
         
    $this->assertRedirectTo('/account/login');

  }

  /**
   *
   *
   * @dataProvider invalidPasswordProvider
   */
  public function testInvalidPasswordFailsRegistration($name, $zip, $email, $pswd, $pswdConfirm)
  {

    $this->request->setMethod('POST')
         ->setPost(array(
             'username'         => $name,
             'zip_code'     => $zip,
             'email'        => $email,
             'password'         => $pswd,
             'confirm_pswd' => $pswdConfirm,
           ));

    $this->dispatch('/account/register');
         
    $this->assertQueryContentContains('#errors', 'Please provide a valid password');

  }

  /**
   * Create two instances of registration attempts involving
   * invalid passwords
   *
   *
   */
  public function invalidPasswordProvider()
  {
    return array(
      array("JasonGilmore", "43215", "wj@example.com", "pswd", "pswd"),
      array("JasonGilmore", "43215", "wj@example.com", "", ""),
    );
  }

  /**
   * The login page exists and returns proper response code
   *
   *
   */
  public function testAccountLoginPageExistsAndProduces200ResponseCode() 
  {
      $this->dispatch('/account/login');
      $this->assertController('account');
      $this->assertAction('login');
      $this->assertResponseCode(200);
  }	

  /**
   * The login page contains the proper title
   *
   *
   */
  public function testLoginViewContainsLoginTitle()
  {
    $this->dispatch('/account/login');
    $this->assertQueryContentContains('title', 'GameNomad: Login to Your Account');
  }

  /**
   * The login page contains the login form and associated form elements
   *
   *
   */
  public function testLoginActionContainsLoginForm()
  {
    $this->dispatch('/account/login');
    $this->assertQueryCount('form#login', 1);
    $this->assertQueryCount('input[name~="email"]', 1);
    $this->assertQueryCount('input[name~="pswd"]', 1);
    $this->assertQueryCount('input[name~="submit"]', 1);
  }

  /**
   * A valid login initializes new session and redirects to home page
   *
   *
   */
	public function testValidLoginInitializesAuthSessionAndRedirectToHomePage()
	{

    $this->_loginValidUser();

	}

  /**
   * An invalid password should not authenticate the user
   *
   *
   */
	public function testInvalidLoginDoesNotRedirectToHomePage()
	{

    $this->_loginInvalidUser();
         
    $this->assertController('account');
    $this->assertAction('login');

    $this->assertNotRedirectTo('/');

	}

  /**
   * The invitation page exists and returns proper response code
   *
   *
   */
  public function testInvitationPageOnlyAvailableToLoggedInUser() 
  {

    $this->_loginValidUser();

    $this->dispatch('/account/invite');

    $this->assertController('account');
    $this->assertAction('invite');

    $this->assertNotRedirectTo('/account/login');

  }	
    
  /**
   * Only authenticated users can access /account/logout
   *
   *
   */
  public function testLogoutPageAvailableToLoggedInUser()
  {

    $this->_loginValidUser();

    $this->dispatch('/account/logout');

    $this->assertController('account');
    $this->assertAction('logout');

    $this->assertNotRedirectTo('/account/login');

  }

}

