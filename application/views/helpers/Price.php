<?php 
  
class Zend_View_Helper_Price extends Zend_View_Helper_Abstract
{ 
      
   /** 
    */ 
   public function Price($productPrice)  
   { 

    $price = "<span class='disabled'>$0.00</span>";

    if ($productPrice != 0.00)
    {

      $price = "\${$productPrice}";

    }

    return $price;

      
  } 

}
