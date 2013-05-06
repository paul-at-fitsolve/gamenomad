<?php 
  
class Zend_View_Helper_ReleaseDate extends Zend_View_Helper_Abstract
{ 
      
  public function ReleaseDate($releaseDate)  
  { 
    return $releaseDate->format('F j, Y'); 
  } 

}
