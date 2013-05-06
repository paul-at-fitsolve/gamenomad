<?php 
  
class Zend_View_Helper_SalesRank extends Zend_View_Helper_Abstract
{ 
      
   /** 
    */ 
   public function SalesRank($salesRank)  
   { 

    return number_format($salesRank);
      
  } 

}
