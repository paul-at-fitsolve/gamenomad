<?php 
  
/**
 *
 *
 *
 *
 */
class Zend_View_Helper_Trend extends Zend_View_Helper_Abstract
{ 
      
   public function Trend($first, $second)  
   { 


    $up = "<img src='/images/icons/arrow_up.png' />";
    $down = "<img src='/images/icons/arrow_down.png' />";
    $sideways = "<img src='/images/icons/arrow_refresh_small.png' />";

    if ($first > $second)
    {
      $difference = $first - $second;
      return $up . "(+{$difference})";
    } else if ($second > $first) {
      $difference = $second - $first;
      return $down . "(-{$difference})";
    } else {
      return $sideways;
    }

    return $value;

  } 

}
