<?php

namespace Entities;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity (repositoryClass="Repositories\Platform") 
 * @Table(name="platforms") 
 * @HasLifecycleCallbacks
 */
class Platform
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=255) */
    private $name;

    /** @Column(type="string", length=10) */
    private $abbreviation;

    /**
     * @OneToMany(targetEntity="Game", mappedBy="games")
     */
    private $games;

    public function __construct() {
      $this->games = new ArrayCollection();
    }

    public function getGames()
    {
      return $this->games;
    }

    public function getId()
    {
      return $this->id;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName($name)
    {
      $this->name = $name;
    }

    public function getAbbreviation()
    {
      return $this->abbreviation;
    }

    public function setAbbreviation($abbreviation)
    {
      $this->abbreviation = $abbreviation;
    }

}
