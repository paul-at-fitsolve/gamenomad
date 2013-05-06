<?php

/**
 * The user profile form
 *
 * This class defines the form elements and validations required for the
 * user profile form.
 *
 *
 */
class Application_Model_FormProfile extends Zend_Form
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        $this->setName('login');
        $this->setMethod('post');
        $this->setAction('/account/profile');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Your Name:');
        $username->setAttrib('size', 35);
        $username->setRequired(true);
        $username->removeDecorator('label');
        $username->removeDecorator('htmlTag');
        $username->removeDecorator('Errors');
        $username->addErrorMessage('Please provide your username');

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

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update Profile');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_profile.phtml'))));

        $this->addElements(array($username, $zipCode, $email, $submit));

    } 

}

