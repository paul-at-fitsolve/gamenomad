#!/usr/bin/php
<?php

/**
 *
 *
 *
 */

include "../../public/cli.php";

// Retrieve the database connection handle
$db = $application->getBootstrap()->getResource('db');

// Retrieve the public and private Amazon web service keys
$amazonPublicKey = Zend_Registry::get('config')->amazon->product_advertising->public->key;
$amazonPrivateKey = Zend_Registry::get('config')->amazon->product_advertising->private->key;
$amazonCountry = Zend_Registry::get('config')->amazon->product_advertising->country;

// Connect to the Amazon Web service
$amazon = new Zend_Service_Amazon($amazonPublicKey, $amazonCountry, $amazonPrivateKey);

// Retrieve all of the games stored in the GameNomad database
$games = $db->fetchAll('SELECT id, asin, name FROM games ORDER BY id');

// Iterate over each game, updating its price
foreach ($games AS $game)
{

  try {

    $item = $amazon->itemLookup($game['asin'], array('ResponseGroup' => 'SalesRank'));
  
    if (! is_null($item)) {
  
      if (isset($item->SalesRank))
      {
  
        $insert = $db->query("INSERT INTO ranks VALUES(NULL, :gameID, :rank, NULL)", 
                          array('gameID' => $game['id'], 'rank' => $item->SalesRank));
  
      }
  
    }

  } catch(Exception $e) {
    echo "Could not find {$game['asin']} in Amazon database\r\n";
  }
  
  // Amazon doesn't like it when you bang on their server too hard, so go slow
  sleep(3);  

}


?>

