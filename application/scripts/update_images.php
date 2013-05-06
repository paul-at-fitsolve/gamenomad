<?php

/**
 * This script is responsible for updating the URLs which point to the video game 
 * cover images used on GameNomad. This update ensures that GameNomad is using 
 * the latest image. While the AWS ToS allows for images to be stored locally, 
 * it's easier just to refer to the URL (which the ToS also allows).    
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

      $smallImage = $item->SmallImage->Url;
      $mediumImage = $item->MediumImage->Url;

      $update = $db->query("UPDATE games SET small = :small, medium = :medium WHERE id = :id", 
                        array('small' => $smallImage, 'medium' => $mediumImage, 'id' => $game['id']));
                        
      echo "{$game['asin']} updated.\r\n";
                        
    }

  } catch(Exception $e) {
    echo "Could not find {$game['asin']} in Amazon database\r\n";
  }

}


?>

