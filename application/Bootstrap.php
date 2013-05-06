<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initModuleLoaders()
    {
        $this->bootstrap('Frontcontroller');

        $fc = $this->getResource('Frontcontroller');
        $modules = $fc->getControllerDirectory();

        foreach ($modules AS $module => $dir) {
            $moduleName = strtolower($module);
            $moduleName = str_replace(array('-', '.'), ' ', $moduleName);
            $moduleName = ucwords($moduleName);
            $moduleName = str_replace(' ', '', $moduleName);

            $loader = new Zend_Application_Module_Autoloader(array(
                'namespace' => $moduleName,
                'basePath' => realpath($dir . "/../"),
            ));
        }
    }

  protected function _initConfig()
  {

    $config = new Zend_Config($this->getOptions());
    Zend_Registry::set('config', $config);

  }

  protected function _initGlobalVars()
  {
  	
    Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/../library/WJG/Controller/Action/Helper');

    $initializer = Zend_Controller_Action_HelperBroker::addHelper(
      new WJG_Controller_Action_Helper_Initializer());

  }

  protected function _initDoctrine() {

    require_once('Doctrine/Common/ClassLoader.php');

    $autoloader = Zend_Loader_Autoloader::getInstance();
    
    // Load the entities
    $classLoader = new \Doctrine\Common\ClassLoader('Entities', 
      realpath(Zend_Registry::get('config')->resources->entityManager->connection->entities), 'loadClass');
    $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Entities');

    // Load the repositories
    $classLoader = new \Doctrine\Common\ClassLoader('Repositories',
      realpath(Zend_Registry::get('config')->resources->entityManager->connection->entities), 'loadClass');

    $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Repositories');     
    
  }

  protected function _initEmail()
  {

    $emailConfig = array('auth'     => 'login',
                         'username' => Zend_Registry::get('config')->email->username,
                         'password' => Zend_Registry::get('config')->email->password,
                         'ssl'      => 'tls',
                         'port'     => Zend_Registry::get('config')->email->port);    		
    		
		$mailTransport = new Zend_Mail_Transport_Smtp(Zend_Registry::get('config')->email->server, $emailConfig);
		    		
		Zend_Mail::setDefaultTransport($mailTransport); 

  }

  public function _initRoutes()
  {

    $frontController = Zend_Controller_Front::getInstance();
    $router = $frontController->getRouter();

    $route = new Zend_Controller_Router_Route_Static (
      'login',
      array('controller' => 'Account', 'action' => 'login')
    );

    $router->addRoute('login', $route);

    $route = new Zend_Controller_Router_Route (
        'games/:asin/',
        array('controller' => 'Games',
              'action'     => 'view'
             )
    );

    $router->addRoute('game-asin-view', $route);

    /**
     * 
     * 
     */
    $route = new Zend_Controller_Router_Route (
        'games/platform/:platform/:page',
        array('controller' => 'Games',
              'action'     => 'platform',
              'page'       => 1
             )
    );

    $router->addRoute('platform-view', $route);
    
    
    /**
     * 
     * 
     */
    $route = new Zend_Controller_Router_Route (
        'games/platform/:platform/hot',
        array('controller' => 'Games',
              'action'     => 'hot'
             )
    );

    $router->addRoute('platform-hot', $route);
    
    /**
     * 
     * 
     */
    $route = new Zend_Controller_Router_Route (
        'profile/:username',
        array('controller' => 'Account',
              'action'     => 'public'
             )
    );

    $router->addRoute('public-profile-view', $route);

    $route = new Zend_Controller_Router_Route (
        'community/page/:page',
        array('controller' => 'Community',
              'action'     => 'index',
              'page'       => 1
             )
    );

    $router->addRoute('community-index-view', $route);

  }



}

