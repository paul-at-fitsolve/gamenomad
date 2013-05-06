<?php

namespace Entities;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity (repositoryClass="Repositories\Account") 
 * @Table(name="accounts") 
 * @HasLifecycleCallbacks
 */
class Account
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string", length=255) */
    private $username;

    /** @Column(type="string", length=255) */
    private $email;

    /** @Column(type="string", length=32) */
    private $password;

    /** @Column(type="string", length=10) */
    private $zip;

    /** @Column(type="decimal", scale=6, precision=10) */
    private $latitude;

    /** @Column(type="decimal", scale=6, precision=10) */
    private $longitude;

    /** @Column(type="smallint") */
    private $confirmed;

    /** @Column(type="string", length=32) */
    private $recovery;

	/** @Column(type="datetime") */
    private $created;

	/** @Column(type="datetime") */    
    private $updated;

    /**
     * @ManyToMany(targetEntity="Game", inversedBy="accounts")
     * @JoinTable(name="accounts_games",
     *      joinColumns={@JoinColumn(name="account_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="game_id", referencedColumnName="id")}
     *      )
     * @OrderBy({"name" = "ASC"})
     */
    private $games;

    /**
     * @ManyToMany(targetEntity="Account", mappedBy="myFriends")
     */
    private $friendsWithMe;
    
    /**
     * @ManyToMany(targetEntity="Account", inversedBy="friendsWithMe")
     * @JoinTable(name="friends",
     *      joinColumns={@JoinColumn(name="account_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="friend_id", referencedColumnName="id")}
     *      )
     */
    private $myFriends;
    
    /**
     * Entity constructor
     */
    public function __construct()
    {

      $this->games = new ArrayCollection();
      
      $this->friendsWithMe = new ArrayCollection();
      
      $this->myFriends = new ArrayCollection();
      
      $this->created = $this->updated = new \DateTime("now");
      
    }

    /**
     * Add friend.
     * @param Account $account
     */
    public function addFriend(Account $friend)
    {
        $this->friendsWithMe[] = $this;
        $this->myFriends[] = $friend;
    }      
    
    /**
     * Get account's friends
     */
    public function getFriends()
    {
      return $this->myFriends;
    }    
    
    /**
     * Add game to account.
     * @param Game $game
     */
    public function addGame(Game $game)
    {
        $game->addAccount($this);
        $this->games[] = $game; 
    }    
    
    public function removeGame(Game $game)
    {
      $this->games->removeElement($game);
      return $this;
    }
    
    public function ownsGame(Game $game)
    {
      if ($this->games->contains($game))
      {
        return TRUE; 
      } else {
        return FALSE;
      }
    }
    
    /**
     * Retrieve games associated with this user
     * 
     */
    public function getGames()
    {
      return $this->games;
    }
    
    
    
    /**
     * @PreUpdate
     */
    public function updated()
    {
        $this->updated = new \DateTime("now");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getConfirmed()
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    public function getRecovery()
    {
        return $this->recovery;
    }

    public function setRecovery($recovery)
    {
        $this->recovery = $recovery;
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
