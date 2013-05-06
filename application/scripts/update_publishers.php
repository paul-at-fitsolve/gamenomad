<?php

/**
 *
 *
 *
 */

include "../public/cli.php";

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

// Iterate over each game, updating its publisher
foreach ($games AS $game)
{

  try {

  $item = $amazon->itemLookup($game['asin'], array('ResponseGroup' => 'Medium'));

  if (! is_null($item)) {

    if (isset($item->Manufacturer))
    {

      $update = $db->query("UPDATE games SET publisher = :publisher WHERE id = :id", 
                        array('publisher' => $item->Manufacturer, 'id' => $game['id']));

    } else {

      $update = $db->query("UPDATE games SET publisher = :publisher WHERE id = :id", 
                        array('publisher' => 'Not available', 'id' => $game['id']));

    }

  } else {

    $update = $db->query("UPDATE games SET publisher = :publisher WHERE id = :id", 
                        array('publisher' => 'Not available', 'id' => $game['id']));

  }

  } catch(Exception $e) {
    echo "Could not find {$game['asin']} in Amazon database\r\n";
  }

}


?>

