<?php

class Application_Model_FormRegister extends Zend_Form
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        $this->setName('login');
        $this->setMethod('post');
        $this->setAction('/account/register');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Your Username:');
        $username->setAttrib('size', 35);
        $username->setRequired(true);
        $username->removeDecorator('label');
        $username->removeDecorator('htmlTag');
        $username->addValidator('Alnum'); 
        $username->removeDecorator('Errors');
        $username->addErrorMessage('Your username can consist solely of letters and numbers');

        $zipCode = new Zend_Form_Element_Text('zip');
        $zipCode->setLabel('Your Zip Code:');
        $zipCode->setAttrib('size', 15);
        $zipCode->setRequired(true);
        $zipCode->removeDecorator('label');
        $zipCode->removeDecorator('htmlTag');
        $zipCode->removeDecorator('Errors');
        $zipCode->addErrorMessage('Please provide a valid zip code');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Your E-mail Address:');
        $email->setAttrib('size', 35);
        $email->setRequired(true);
        $email->addValidator('EmailAddress');
        $email->removeDecorator('label');
        $email->removeDecorator('htmlTag');
        $email->removeDecorator('Errors');
        $email->addErrorMessage('Please provide a valid e-mail address');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');
        $password->setAttrib('size', 35);
        $password->setRequired(true);
        $password->addValidator('StringLength', false, array('min' => 6));
        $password->removeDecorator('label');
        $password->removeDecorator('htmlTag');
        $password->removeDecorator('Errors');
        $password->addErrorMessage('Please provide a valid password');

        $confirmPswd = new Zend_Form_Element_Password('confirm_pswd');
        $confirmPswd->setLabel('Confirm Password:');
        $confirmPswd->setAttrib('size', 35);
        $confirmPswd->removeDecorator('label');
        $confirmPswd->removeDecorator('htmlTag');
        $confirmPswd->addValidator('Identical', false, array('token' => 'password'));
        $confirmPswd->removeDecorator('Errors');
        $confirmPswd->addErrorMessage('The passwords do not match');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Create Your Account');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_register.phtml'))));

        $this->addElements(array($username, $zipCode, $email, $password, $confirmPswd, $submit));

    } 

}

