<?php

namespace Entities;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity (repositoryClass="Repositories\Rank")
 * @Table(name="ranks") 
 * @HasLifecycleCallbacks
 */
class Rank
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="integer") */
    private $rank;

    /**
     * @ManyToOne(targetEntity="Game", inversedBy="ranks")
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

	/** @Column(type="datetime") */
    private $created;    
    
    public function __construct() {
      $this->games = new ArrayCollection();
    }

    public function setGame($game)
    {
      return $this->game = $game;
    }      
    
    public function getGame()
    {
      return $this->game;
    }    
    
    public function getId()
    {
      return $this->id;
    }

    public function getRank()
    {
      return $this->rank;
    }

    public function setRank($rank)
    {
      $this->rank = $rank;
    }

    public function getCreated()
    {
      return $this->created;
    }

    public function setCreated($created)
    {
      $this->created = $created;
    }

}
