<?php

class GamesControllerTest extends ControllerTestCase
{

  /**
   *
   *
   *
   */	
  public function testDoesGameHomePageExist() 
  {
    $this->dispatch('/games/');
    $this->assertController('games');
    $this->assertAction('index');
  }	

  /**
   *
   *
   *
   */	
  public function testExactlyFiveHotGamesAreDisplayed()
  {
    $this->dispatch('/games/platform/360');
    $this->assertQueryCount('.game-sidebar', 5);
  }

  /**
   *
   *
   *

  public function testDoesGamePlatformHomePageExist() 
  {
    $this->dispatch('/games/platform/');
    $this->assertController('games');
    $this->assertAction('platform');
  }	
   */

}

