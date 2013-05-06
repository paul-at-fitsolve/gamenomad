<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


/**
 * Platform Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Platform extends EntityRepository
{
  
  /**
   * How many games is GameNomad currently tracking for this game?
   * @param integer $platformID
   */
  public function countGames($platformID)
  {
    
    $platformID = (int)$platformID;
    
    $query = $this->_em->createQuery(
      'SELECT COUNT(g.id) FROM Entities\Game g WHERE g.platform = :pid'
    );
    
    $query->setParameter('pid', $platformID);
    
    return $query->getSingleScalarResult();
    
  }
    
  /**
   * Paginate games
   * 
   * @param integer $platformID
   * @param integer $count
   * @param integer $page
   */
  public function paginateGames($platformID, $page, $gamesPerPage) {
   
    $rsm = new ResultSetMapping;
    
    $page--;
    
    $offset = $gamesPerPage * (int)$page;
    
    $rsm->addEntityResult('Entities\Game', 'g');
    $rsm->addFieldResult('g', 'id', 'id');
    $rsm->addFieldResult('g', 'name', 'name');
    $rsm->addFieldResult('g', 'asin', 'asin');
    $rsm->addFieldResult('g', 'medium', 'medium');
    
    $query = $this->_em->createNativeQuery(
      'SELECT g.id, g.name, g.asin, g.medium FROM games g 
       WHERE g.platform_id = ? ORDER BY g.name ASC LIMIT ?, ?',
       $rsm
    );
     
    $query->setParameter(1, $platformID, Type::INTEGER);
    $query->setParameter(2, $offset, Type::INTEGER);
    $query->setParameter(3, $gamesPerPage, Type::INTEGER);
        
    return $query->getResult();
    
  }  
  

}