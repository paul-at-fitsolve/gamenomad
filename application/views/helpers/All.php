<?php 
  
class Zend_View_Helper_All extends Zend_View_Helper_Abstract
{ 
      
   /** 
    */ 
   public function All($games)
   { 

$output = <<< OUTPUT
<table>
<tr>
<th>Username</th>
<th>Zip Code</th>
<th>Game</th>
<th>Status</th>
<th>Price</th>
<th>Learn More</th>
</tr>
OUTPUT;

foreach ($games AS $game) {

  $output .= "
  <tr>
  <td><a href=\"/account/profile/{$game['username']}\">{$game['username']}</a></td>
  <td><a href=\"/community/{$game['zip_code']}\">{$game['zip_code']}</a></td>
  <td><a href=\"/games/{$game['asin']}\">{$game['name']}</a></td>
  <td>{$game['status']}</td>
  <td>\${$game['price']}</td>
  <td><a href=\"/account/profile/{$game['username']}\">Contact this user</a></td>
  </tr>
  ";

}
$output .= "</table>";
      
return $output;

  } 

}
