<?php

    class Zend_View_Helper_GameGridEntry extends Zend_View_Helper_Abstract
    {
    	
    	public function GameGridEntry($game) 
    	{
    		   		
        $asin = $game->findParentRow('Application_Model_Game')->asin;

        $mediumImage = rawurlencode($game->findParentRow('Application_Model_Game')->image_medium);

        $name = $game->findParentRow('Application_Model_Game')->name;

        return "<a href=\"/games/{$asin}/\"><img src=\"/images/amazon/{$mediumImage}\" class=\"game_cover\" alt=\"\" />
                </a><br /><b><a href=\"/games/{$asin}/\">{$name}</a></b>";
    		
    	}
    	    	
    }

?>
