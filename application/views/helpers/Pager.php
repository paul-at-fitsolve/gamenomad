<?php 
  
class Zend_View_Helper_Pager extends Zend_View_Helper_Abstract
{ 
      
   /** 
    * 
    * @param integer $pager
    * @param integer $currentPage
    * @param string $path
    * @return string
    */ 
   public function Pager($pages, $currentPage, $path)  
   { 

    $pageRange = "";
 
    $pages = range(1, $pages);
    
    foreach($pages AS $page)
    {

      if ($page != $currentPage)
      {
        $pageRange .= "<a href='$path/{$page}'>[{$page}]</a> ";
      } else {
        $pageRange .= "[{$currentPage}] ";
      }

    }

    return $pageRange;

  } 

}
