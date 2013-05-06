<?php 
  
class Zend_View_Helper_Trade extends Zend_View_Helper_Abstract
{ 
      
   /** 
    */ 
   public function Trade($games)
   { 

$output = <<< OUTPUT
<table>
<tr>
<th>Username</th>
<th>Zip Code</th>
<th>Game</th>
<th>Learn More</th>
</tr>
OUTPUT;

foreach ($games AS $game) {

  $output .= "
  <tr>
  <td><a href=\"/accounts/{$game['username']}\">{$game['username']}</a></td>
  <td><a href=\"/community/{$game['zip_code']}\">{$game['zip_code']}</a></td>
  <td><a href=\"/games/{$game['asin']}\">{$game['name']}</a></td>
  <td>{$game['status']}</td>
  <td><a href=\"\">Contact this user</a></td>
  </tr>
  ";

}
$output .= "</table>";
      
return $output;

  } 

}
