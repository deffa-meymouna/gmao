<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administration\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="te_role_rol")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Role implements HierarchicalRoleInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="rol_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="rol_libelle", type="string", length=255, unique=true, nullable=false)
     */
    protected $roleId;

    /**
     * @var boolean
     * @ORM\Column(name="rol_default", type="boolean", nullable=false)
     */
    protected $default = false;
    
    /**
     * @var boolean
     * @ORM\Column(name="rol_authenticate", type="boolean", nullable=false)
     */
    protected $authenticate = false;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="rol_inscription", type="UTCDateTime", nullable=false)
     */
    protected $inscription;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="rol_modification", type="UTCDateTime")
     */
    protected $modification;
    
    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Role")
     * @ORM\JoinColumn(name="rol_parent", referencedColumnName="rol_id")
     */
    protected $parent;

    /**
     * @return the $inscription
     */
    public function getInscription()
    {
        return $this->inscription;
    }

	/**
     * @return the $modification
     */
    public function getModification()
    {
        return $this->modification;
    }

	/**
     * @param DateTime $inscription
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;
    }

	/**
     * @param DateTime $modification
     */
    public function setModification($modification)
    {
        $this->modification = $modification;
    }

	/**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
	/**
     * Set the id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return the $default
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return the $default
     */
    public function isDefault()
    {
        return $this->default;
    }
    
    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = !empty($default);
    }

    
	/**
     * @return the $authenticate
     */
    public function getAuthenticate()
    {
        return $this->authenticate;
    }
    
    /**
     * @return the $authenticate
     */
    public function isAuthenticate()
    {
        return $this->authenticate;
    }

	/**
     * @param boolean $authenticate
     */
    public function setAuthenticate($authenticate)
    {
        $this->authenticate = !empty($authenticate);
    }

	/**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set the role id.
     *
     * @param string $roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        $this->roleId = (string) $roleId;
    }

    /**
     * Get the parent role
     *
     * @return Role
     */
    public function getParent()
    { 
        return $this->parent;
    }

    /**
     * Set the parent role.
     *
     * @param Role $role
     *
     * @return void
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;
    }
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function exchangeArray ($data = array())
    {
        $this->id = $data['id'];
        $this->parent = $data['parent'];
        $this->displayName = $data['displayName'];
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
}
