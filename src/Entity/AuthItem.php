<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 06.03.18
 * Time: 12:31
 */
namespace KotaShade\DoctrineRbac\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AuthItem
 * @package KotaShade\DoctrineRbac\Entity
 * @ORM\Table(name="auth_item",
 *     indexes={
 *     },
 *     uniqueConstraints={
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="smallint")
 *
 * @ORM\DiscriminatorMap({
 *          "1" = "AuthItemRole",
 *          "2" = "AuthItemPermission"
 *      })
 * @ORM\Entity()
 */
abstract class AuthItem
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var AuthRule
     * @ORM\ManyToOne(targetEntity="AuthRule", inversedBy="items")
     * @ORM\JoinColumn(name="rule_name", referencedColumnName="name")
     */
    protected $ruleName;

    /**
     * @var string
     * @ORM\Column(name="data", type="string", nullable=true)
     */
    protected $data;

    /**
     * @var integer
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    protected $createdAt;
    /**
     * @var integer
     * @ORM\Column(name="updated_at", type="integer", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AuthItemChild", mappedBy="parent")
     */
    private $children;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AuthItemChild", mappedBy="child")
     */
    private $parents;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return AuthRule
     */
    public function getRuleName(): AuthRule
    {
        return $this->ruleName;
    }

    /**
     * @param AuthRule $ruleName
     */
    public function setRuleName(AuthRule $ruleName): void
    {
        $this->ruleName = $ruleName;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     * @return self
     */
    public function setCreatedAt(int $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @param int $updatedAt
     */
    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param ArrayCollection $parents
     */
    public function setParents( $parents)
    {
        $this->parents = $parents;
    }

    public function __toString()
    {
        return sprintf("%s : name=%s\n", get_class($this), $this->getName());

        // TODO: Implement __toString() method.
    }


}