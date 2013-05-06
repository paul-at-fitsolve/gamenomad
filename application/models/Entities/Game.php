<?php

namespace Entities;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity (repositoryClass="Repositories\Game")
 * @Table(name="games") 
 * @HasLifecycleCallbacks
 */
class Game 
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=255) */
    private $asin;

    /** @Column(type="string", length=255) */
    private $name;

    /** @Column(type="string", length=255) */
    private $publisher;
    
    /** @Column(type="date") */
    private $rel;

    /** @Column(type="string", length=255) */
    private $small;

    /** @Column(type="string", length=255) */
    private $medium;

    /** @Column(type="decimal",scale=2, precision=5) */
    private $price;

    /** @Column(type="string", length=19) */
    private $created;

    /** @Column(type="string", length=19) */
    private $updated;

    /** 
     * @ManyToMany(targetEntity="Account", mappedBy="games")
     */  
    private $accounts;

    /**
     * @ManyToOne(targetEntity="Platform", inversedBy="games")
     * @JoinColumn(name="platform_id", referencedColumnName="id")
     */
    private $platform;

    /**
     * @OneToMany(targetEntity="Rank", mappedBy="game")
     */
    private $ranks;    
    
    private $latest;
    
    public function __construct()
    {

      $this->created = $this->updated = date('Y-m-d H:i:s');
      $this->accounts = new ArrayCollection();
      $this->ranks = new ArrayCollection();

    }

    /**
     * @PreUpdate
     */
    public function updated()
    {
      $this->updated = date('Y-m-d H:i:s');
    }
    
    /**
     * Associate account with game
     * @param Account $account
     */
    public function addAccount(Account $account)
    {
        $this->accounts[] = $account;
    }
    
    /**
     * Retrieve accounts associated with game
     * 
     */
    public function getAccounts()
    {
      return $this->accounts;
    }

    /**
     * Retrieve the game's sales rank history
     * 
     */
    public function getRanks()
    {
        return $this->ranks;
    }

    /**
     * Retrieve the latest sales rank. Because the ranks are
     * returned as a persistent collection, we can use the last()
     * method to retrieve the most recent rank.
     * 
     */
    public function getLatestRank()
    {
      $ranks = $this->getRanks();
      $rank = $ranks->last();
      if ($rank) {
        return $rank->getRank();
      } else {
        return 0;
      }
    }
    
    public function setRank($rank)
    {
        $this->ranks[] = $rank;
    }    
    
    public function getId()
    {
        return $this->id;
    }

    public function getAsin()
    {
        return $this->asin;
    }

    public function setAsin($asin)
    {
        $this->asin = $asin;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPlatform()
    {
        return $this->platform;
    }    
    
    public function getPublisher()
    {
        return $this->publisher;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    public function getRelease()
    {
        return $this->rel;
    }

    public function setRelease($release)
    {
        $this->rel = $release;
    }

    public function getSmall()
    {
        return $this->small;
    }

    public function setSmall($small)
    {
        $this->small = $small;
    }

    public function getMedium()
    {
        return $this->medium;
    }

    public function setMedium($medium)
    {
        $this->medium = $medium;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
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
