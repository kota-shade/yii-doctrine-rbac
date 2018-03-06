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
 * Class AuthItemChild
 * //TODO определить уникальную пару
 * @package KotaShade\DoctrineRbac\Entity
 * @ORM\Table(name="auth_item_child",  indexes={
 *      },
 *      uniqueConstraints={
 *      }
 * )
 * @ORM\Entity()
 */
class AuthItemChild
{

    /**
     * @var AuthItem
     *
     * @ORM\ManyToOne(targetEntity="AuthItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="name", nullable=false)
     * @ORM\Id
     */
    private $parent;

    /**
     * @var AuthItem
     *
     * @ORM\ManyToOne(targetEntity="AuthItem", inversedBy="parents")
     * @ORM\JoinColumn(name="child", referencedColumnName="name", nullable=false)
     * @ORM\Id
     */
    private $child;

    /**
     * @return AuthItem
     */
    public function getParent(): AuthItem
    {
        return $this->parent;
    }

    /**
     * @param AuthItem $parent
     */
    public function setParent(AuthItem $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return AuthItem
     */
    public function getChild(): AuthItem
    {
        return $this->child;
    }

    /**
     * @param AuthItem $child
     */
    public function setChild(AuthItem $child): void
    {
        $this->child = $child;
    }

}