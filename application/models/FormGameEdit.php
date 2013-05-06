<?php

class Application_Model_FormGameEdit extends Zend_Form
{

    public function __construct($options = null)
    {

      $this->setName('game_edit_form');
      $this->setMethod('post');
      $this->setAction('/account/editgame');

      $asin = new Zend_Form_Element_Hidden('asin');
      $asin->removeDecorator('label');
      $asin->removeDecorator('htmlTag');
      $asin->removeDecorator('Errors');

      $accountID = $options['account_id'];

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();

      $options = $db->fetchPairs(
         $db->select()->from('status', array('id', 'name'))
           ->order('name ASC'), 'id');

      $status = new Zend_Form_Element_Select('status');

      $status->AddMultiOptions($options);
      $status->removeDecorator('label');
      $status->removeDecorator('htmlTag');
      $status->removeDecorator('Errors');

      // TODO
      $options2 = array("0" => "No friends");

      $borrower = new Zend_Form_Element_Select('borrower');

      $borrower->AddMultiOptions($options2);
      $borrower->removeDecorator('label');
      $borrower->removeDecorator('htmlTag');
      $borrower->removeDecorator('Errors');

      $price = new Zend_Form_Element_Text('price');
      $price->setAttrib('size', 5);
      $price->removeDecorator('label');
      $price->removeDecorator('htmlTag');
      $price->removeDecorator('Errors');

      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Update Game');
      $submit->removeDecorator('DtDdWrapper');
      $this->setDecorators( array( array('ViewScript', array('viewScript' => '_form_game_edit.phtml'))));

      $this->addElements(array($asin, $status, $borrower, $price, $submit));

    }

}

