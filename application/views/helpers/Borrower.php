<?php 
  
class Zend_View_Helper_Borrower extends Zend_View_Helper_Abstract
{ 
      
   /** 
    */ 
   public function Borrower($game)  
   { 

    $borrowerName = "<span class='disabled'>N/A</span>";

/*
    if (! is_null($game->Borrower->id))
    {
      return $game->Borrower->Account->name;
    } else {
      return $borrowerName;
    }
*/  
    
  } 

}
