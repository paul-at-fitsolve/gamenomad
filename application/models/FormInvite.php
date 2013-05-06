<?php

/**
 *
 *
 *
 */
class Application_Model_FormInvite extends Zend_Form
{

    public function __construct($options = null)
    {

      $this->setName('contact');
      $this->setMethod('post');
      $this->setAction('/account/invite');

      $username = new Zend_Form_Element_Text('username');
      $username->setAttrib('size', 35);
      $username->setRequired(true);
      $username->addErrorMessage('Please provide your name');
      $username->removeDecorator('label');
      $username->removeDecorator('htmlTag');
      $username->removeDecorator('Errors');

      $name = new Zend_Form_Element_Text('name');
      $name->setAttrib('size', 35);
      $name->setRequired(true);
      $name->addErrorMessage('Please provide your friend\'s name');
      $name->removeDecorator('label');
      $name->removeDecorator('htmlTag');
      $name->removeDecorator('Errors');

      $email = new Zend_Form_Element_Text('email');
      $email->setAttrib('size', 35);
      $email->setRequired(true);
      $email->addErrorMessage('Please provide a valid e-mail address');
      $email->addValidator('EmailAddress');
      $email->removeDecorator('label');
      $email->removeDecorator('htmlTag');
      $email->removeDecorator('Errors');

      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Invite Your Friend!');
      $submit->removeDecorator('DtDdWrapper');
      $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_invite.phtml'))));

      $this->addElements(array($username, $name, $email, $submit));

    } 

}

