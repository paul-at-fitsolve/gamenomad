<?php

    class Zend_View_Helper_Pluralizer extends Zend_View_Helper_Abstract
    {
    	
    	public function Pluralizer($value, $singleText, $pluralText) 
    	{
    		   		
    		if ($value > 1 OR $value == 0) {
    	    return "{$value} {$pluralText}";
    		} else {
    			return "{$value} {$singleText}";
        }
    		
    	}
    	    	
    }

?>
