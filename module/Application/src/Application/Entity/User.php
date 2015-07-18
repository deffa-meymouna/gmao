<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Application\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;
use ZfcUserDoctrineORM\Entity\User as DoctrineUser;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="te_user_usr")
 *
 * @author Tom Oram <tom@scl.co.uk>
 * @author Alexandre Tranchant <alexandre.tranchant@gmail.com>
 */
class User extends DoctrineUser implements UserInterface, ProviderInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="usr_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="usr_username", type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(name="usr_email", type="string", unique=true,  length=255, nullable=false)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="usr_display_name", type="string", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(name="usr_password", type="string", length=128, nullable=false)
     */
    protected $password;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="usr_inscription", type="UTCDateTime", nullable=false)
     */
    protected $inscription;

    /**
     * @var \DateTime
     * @ORM\Column(name="usr_activation", type="UTCDateTime")
     */
    protected $activation;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="usr_bannissement", type="UTCDateTime")
     */
    protected $bannissement;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="usr_lastvisite", type="UTCDateTime")
     */
    protected $lastVisite;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="usr_modification", type="UTCDateTime")
     */
    protected $modification;
    /**
     * @var string
     * @ORM\Column(name="usr_timezone", type="string", length=80, nullable=false)
     */
    protected $timezone;
    /**
     * @var string
     * @ORM\Column(name="usr_locale", type="string", length=80, nullable=false)
     */
    protected $locale;
    
    
    /**
     * @var int
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Application\Entity\Role")
     * @ORM\JoinTable(name="tj_user_role_linker_url",
     *      joinColumns={@ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="rol_id")}
     * )
     */
    protected $roles;
    
    /**
     * @var bool
     */
    private $localizedInscription = false;

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }
    
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array())
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->username = $data['username'];
        $this->displayName = $data['displayName'];
        //@FIXME gérer le changement de mot de passe ?
    }    

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        if (empty($this->displayName)){
            if (empty($this->getUsername())){
                return substr($this->getEmail(), 0, strpos($this->getEmail(), '@'));
            }
        }
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return DateTime the $inscription
     */
    public function getInscription()
    {
        /*if (!$this->localizedInscription) {
            $this->inscription->setTimeZone(new \DateTimeZone($this->timezone));
        }*/
        return $this->inscription;
    }

	/**
     * @param DateTime $inscription
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;
    }

	/**
     * @return DateTime the $activation
     */
    public function getActivation()
    {
        return $this->activation;
    }

	/**
     * @param DateTime $activation
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;
    }

	/**
     * @return DateTime the $bannissement
     */
    public function getBannissement()
    {
        return $this->bannissement;
    }

	/**
     * @param DateTime $bannissement
     */
    public function setBannissement($bannissement)
    {
        $this->bannissement = $bannissement;
    }

	/**
     * @return DateTime the $lastvisite
     */
    public function getLastVisite()
    {
        return $this->lastVisite;
    }

	/**
     * @param DateTime $lastvisite
     */
    public function setLastVisite($lastVisite)
    {
        $this->lastVisite = $lastVisite;
    }

	/**
     * @return DateTime the $modification
     */
    public function getModification()
    {
        return $this->modification;
    }

	/**
     * @param DateTime $modification
     * @return void
     */
    public function setModification($modification)
    {
        $this->modification = $modification;
    }

	/**
     * @return string the $timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

	/**
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

	/**
     * @return string the $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

	/**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

	/**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }
    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRoles(Collection $roles)
    {
        foreach($roles as $role){
            $this->roles->add($role);
        }
    
    }
    /**
     * Remove a collection of roles to the user
     * 
     * @param Collection $roles
     * 
     * @return void
     */
    public function removeRoles(Collection $roles) 
    {
        foreach($roles as $role){
            $this->roles->removeElement($role);
        }
    }
    /**
     * Indique si l'utilisateur est actif
     * 
     * @return boolean true si actif
     */
    public function isActive(){
        return null != $this->activation;
    }
    /**
     * Indique si l'utilisateur est banni
     *
     * @return boolean true si banni
     */
    public function isBanned(){
        return null != $this->bannissement;
    }
    /**
     * Met à jour la date de dernière modification
     */
    public function setUpdated()
    {
        // WILL be saved in the database
        $this->modification = new \DateTime("now");
    }
}