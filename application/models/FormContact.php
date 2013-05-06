<?php

/**
 *
 *
 *
 */
class Application_Model_FormContact extends Zend_Form
{

    public function __construct($options = null)
    {

      $this->setName('contact');
      $this->setMethod('post');
      $this->setAction('/about/contact');

      $name = new Zend_Form_Element_Text('name');
      $name->setAttrib('size', 35);
      $name->setRequired(true);
      $name->addErrorMessage('Please provide your name');
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

      $message = new Zend_Form_Element_Textarea('message');
      $message->setRequired(true);
      $message->setAttrib('id','textarea_message');
      $message->addErrorMessage('Please specify a message');
      $message->removeDecorator('label');
      $message->removeDecorator('htmlTag');
      $message->removeDecorator('Errors');

      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Send Your Message!');
      $submit->removeDecorator('DtDdWrapper');
      $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_contact.phtml'))));

      $this->addElements(array($name, $email, $message, $submit));

    } 

}

