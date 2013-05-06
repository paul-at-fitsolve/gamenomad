<?php 
  
   class Zend_View_Helper_Status extends Zend_View_Helper_Abstract
   { 
          
     /** 
      */ 
     public function Status($status, $setting=2)  
     { 

      $selected = "";
              
      $selectBox = '<select name="status" class="game_status">';

      foreach($status as $s)
      {

        if ($setting == $s->id)
        {
          $selected = "selected";
        } else {
          $selected = "";
        }

        $selectBox .= "<option value=\"{$s->id}\" {$selected}>{$s->name}</option>";

      }

      $selectBox .= "</select>";

      return $selectBox;
  
     } 
          
   } 
  
?>
