<?php 

/**
 * @author "W. Jason Gilmore <wj@wjgilmore.com>"
 *
 *
 *
 */
class AboutController extends Zend_Controller_Action
{

    /**
     * About GameNomad
     *
     *
     *
     *
     */
    public function indexAction()
    {
    }

    /**
     * About the book
     *
     *
     *
     *
     */
    public function bookAction()
    {
    }

    /**
     * Contact the GameNomad team
     *
     *
     *
     *
     */
    public function contactAction()
    {
    $form = new Application_Model_FormContact();

    if ($this->getRequest()->isPost()) {

        if ($form->isValid($this->_request->getPost())) {

          try {

            $mail = new Zend_Mail();

            $mail->setFrom($form->getValue('email'));
            $mail->addTo(Zend_Registry::get('config')->email->support, "GameNomad Support");
            $mail->setSubject('GameNomad.com: Support Request');
                  
            $mail->setBodyText($form->getValue('message'));
            $mail->send();

            $this->_helper->flashMessenger
                 ->addMessage(Zend_Registry::get('config')->messages->contact->successful);
            $this->_helper->redirector('index', 'index');

          } catch(Exception $e) {
            $this->view->errors[] = "There was a problem processing your request.";
          }

        } else {
          $this->view->errors = $form->getErrors();
        }

    }

    $this->view->form = $form;
    }

    /**
     * Contact the GameNomad team
     *
     *
     *
     *
     */
    public function registerAction()
    {
        // action body
    }

    public function contestAction()
    {
        // action body
    }


}