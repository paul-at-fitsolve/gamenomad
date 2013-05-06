<?php

/**
 *
 *
 *
 */

include "../public/cli.php";

// Retrieve the database connection handle
$db = $application->getBootstrap()->getResource('db');

// Retrieve the Amazon web service configuration data
$amazonPublicKey = Zend_Registry::get('config')->amazon->product_advertising->public->key;
$amazonPrivateKey = Zend_Registry::get('config')->amazon->product_advertising->private->key;
$amazonCountry = Zend_Registry::get('config')->amazon->product_advertising->country;

// Connect to the Amazon Web service
$amazon = new Zend_Service_Amazon($amazonPublicKey, $amazonCountry, $amazonPrivateKey);

// Retrieve all of the games stored in the GameNomad database
$games = $db->fetchAll('SELECT id, asin, name FROM games ORDER BY id ASC');

// Iterate over each game, updating its price
foreach ($games AS $game)
{

  try {

    $item = $amazon->itemLookup($game['asin'], array('ResponseGroup' => 'Medium'));

    if (! is_null($item)) {

      if (isset($item->FormattedPrice))
      {
        $price = substr($item->FormattedPrice, 1);
      } else {
        $price = '$0.00';
      }

      $update = $db->query("UPDATE games SET price = :price WHERE id = :id", 
                        array('price' => $price, 'id' => $game['id']));
                        
      echo "{$game['asin']} updated to {$price} \r\n";
                        
    }

  } catch(Exception $e) {
    echo "Could not find {$game['asin']} in Amazon database\r\n";
  }

}


?>

