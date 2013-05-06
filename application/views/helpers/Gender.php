<?php 
  
   class Zend_View_Helper_Gender extends Zend_View_Helper_Abstract
   { 
          
     /** 
      * Produces string based on whether value is
      * masculine or feminine 
      *  
      * @param string $gender 
      * @return string 
      */ 
     public function Gender($gender)  
     { 
              
       if ($gender == "m") { 
         return "he"; 
       } else { 
         return "she"; 
       } 
  
     } 
          
   } 
  
?>
