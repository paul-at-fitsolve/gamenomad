<?php

class AboutControllerTest extends ControllerTestCase
{
	
  /**
   *
   *
   *
   */
  public function testDoesAboutIndexPageExist() 
  {
    $this->dispatch('/about');
    $this->assertController('about');
    $this->assertAction('index');
  }	
    
  /**
   *
   *
   *
   */
  public function testDoesAboutContactPageExist() 
  {
    $this->dispatch('/about/contact');
    $this->assertController('about');
    $this->assertAction('contact');
  }

  /**
   * Does the contact form exist?
   *
   *
   *
   */
  public function testContactActionContainsContactForm()
  {
    $this->dispatch('/about/contact');
    $this->assertQueryCount('form#contact', 1);
    $this->assertQueryCount('input[name~="name"]', 1);  
    $this->assertQueryCount('input[name~="email"]', 1);
    $this->assertQueryCount('textarea[name~="message"]', 1);
    $this->assertQueryCount('input[name~="submit"]', 1);
  }

  /**
   * 
   * 
   * 
   */
  public function invalidContactInfoProvider()
  {
    return array(
      array("Jason Gilmore", "", "Name and Message but no e-mail address"),
      array("", "wj@example.com", "E-mail address and message but no name"),
      array("", "", "No name or e-mail address"),
      array("No E-mail address or message", "", ""),
      array("Jason Gilmore", "InvalidEmailAddress", "Invalid e-mail address")
    );
  }

 /**
  *
  *
  * @dataProvider invalidContactInfoProvider
  */
  public function testIsInvalidContactInformationDetected($name, $email, $message)
  {

    $this->request->setMethod('POST')
         ->setPost(array(
             'name'        => $name,
             'email'       => $email,
             'message'     => $message
           ));

    $this->dispatch('/about/contact');
         
    $this->assertNotRedirectTo('/');

  }

 /**
  *
  *
  */
  public function testIsValidContactInformationEmailedToSupport()
  {

    $this->request->setMethod('POST')
         ->setPost(array(
             'name'        => 'Jason Gilmore',
             'email'       => 'wj@wjgilmore.com',
             'message'     => "This is my test message."
           ));

    //$this->dispatch('/about/contact');
         
    //$this->assertRedirectTo('/');

  }


}

