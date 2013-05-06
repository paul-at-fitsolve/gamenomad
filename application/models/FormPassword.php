<?php

/**
 * TODO: Write custom validator capable of checking for identical password 
 *
 *
 *
 */
class Application_Model_FormPassword extends Zend_Form
{

    public function __construct($options = null)
    {

        parent::__construct($options);

        $this->setName('change_password');
        $this->setMethod('post');
        $this->setAction('/account/password');

        $pswd = new Zend_Form_Element_Password('pswd');
        $pswd->setLabel('New Password:');
        $pswd->setAttrib('size', 35);
        $pswd->setRequired(true);
        $pswd->removeDecorator('label');
        $pswd->removeDecorator('htmlTag');
        $pswd->removeDecorator('Errors');
        $pswd->addValidator('StringLength', false, array(4,15));
        $pswd->addErrorMessage('Please choose a password between 4-15 characters');


        $confirmPswd = new Zend_Form_Element_Password('confirm_pswd');
        $confirmPswd->setLabel('Confirm New Password:');
        $confirmPswd->setAttrib('size', 35);
        $confirmPswd->setRequired(true);
        $confirmPswd->removeDecorator('label');
        $confirmPswd->removeDecorator('htmlTag');
        $confirmPswd->removeDecorator('Errors');
        $confirmPswd->addValidator('Identical', false, array('token' => 'pswd'));
        $confirmPswd->addErrorMessage('The passwords do not match');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update Password');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_password.phtml'))));

        $this->addElements(array($pswd, $confirmPswd, $submit));

    } 

}

