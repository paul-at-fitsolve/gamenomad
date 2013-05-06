<?php

/**
 *
 *
 *
 */

include "../../public/cli.php";

date_default_timezone_set('Europe/London');

// Retrieve the database connection handle
$db = $application->getBootstrap()->getResource('db');

$amazonPublicKey = Zend_Registry::get('config')
                     ->amazon->product_advertising->public->key;
$amazonPrivateKey = Zend_Registry::get('config')
                     ->amazon->product_advertising->private->key;

$amazonCountry = Zend_Registry::get('config')->amazon->product_advertising->country;

$amazon = 
  new Zend_Service_Amazon($amazonPublicKey, $amazonCountry, $amazonPrivateKey);

// Retrieve the list of ASINs
$asins = file("asins.txt");

// Cycle through each ASIN, looking up the product via the Amazon API
foreach ($asins as $asin)
{

  echo "Searching for {$asin}";

  $asin = rtrim($asin);

  try {
    $item = $amazon->itemLookup($asin, array('ResponseGroup' => 'Medium',
            'AssociateTag' => 'clasmusimaga-21'
    ));
    $platform = $item->Platform;
    $name = $item->Title;
    $manufacturer = $item->Manufacturer;
    $rel = $item->ReleaseDate;
    $smallImage = $item->SmallImage->Url;
    $mediumImage = $item->MediumImage->Url;
    $price = substr($item->FormattedPrice, 1);
    $createdAt = date('Y-m-d H:i:s');
    $updatedAt = date('Y-m-d H:i:s');

    if ($platform == "Xbox 360")
    {
        $platform = 1;
    } else if ($platform == "PlayStation 3") {
        $platform = 2;
    } else if ($platform == "Nintendo Wii") {
        $platform = 3;
    } else {  
        $platform = 0;
    }

    if ($platform != 0) {

    $insert = $db->query("INSERT INTO games VALUES (NULL, :platform, :asin, :name, 
      :publisher, :rel, :small, :medium, :price, :createdAt, :updatedAt)", 
      array('platform' => $platform, 'asin' => $asin, 'name' => $name, 'publisher' => $manufacturer,
            'rel' => $rel, 'small' => $smallImage, 'medium' => $mediumImage, 'price' => $price,
            'createdAt' => $createdAt, 'updatedAt' => $updatedAt));
 
    }

  } catch(Exception $e) {

    echo "{$asin}: " . $e->getMessage();

  }

  // Amazon doesn't like it when you bang on their server too hard, so go slow
  sleep(3);

}

?>

