<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 06.03.18
 * Time: 12:36
 */
namespace KotaShade\DoctrineRbac\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * NB!!! Extend class and define $userproperty - relation to user entity
 *
 * table auth_assignment
 * Class AuthAssignment
 * @package KotaShade\DoctrineRbac\Entity
 *
 * @ORM\MappedSuperclass
 */
abstract class AuthAssignment
{
    /**
     * @var AuthItem
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="KotaShade\DoctrineRbac\Entity\AuthItem")
     * @ORM\JoinColumn(name="item_name", referencedColumnName="name", nullable=false)
     */
    protected $itemName;

    /**
     * @var integer
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    protected $createdAt;

    /**
     * @return object
     */
    abstract public function getUser();
    /**
     * @param object $userE
     */
    abstract public function setUser($userE);

    /**
     * @return AuthItem
     */
    public function getItemName(): AuthItem
    {
        return $this->itemName;
    }

    /**
     * @param AuthItem $itemName
     * @return self
     */
    public function setItemName(AuthItem $itemName)
    {
        $this->itemName = $itemName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

}