<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 06.03.18
 * Time: 12:29
 */
namespace KotaShade\DoctrineRbac\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AuthRule
 * @package KotaShade\DoctrineRbac\Entity
 * @ORM\Table(name="auth_rule",  indexes={
 *      },
 *      uniqueConstraints={
 *      }
 * )
 * @ORM\Entity()
 */
class AuthRule
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     * @ORM\Id
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="data", type="string", nullable=false)
     */
    private $data;
    /**
     * @var integer
     * @ORM\Column(name="created_at", type="integer", nullable=true)
     */
    private $createdAt;
    /**
     * @var integer
     * @ORM\Column(name="updated_at", type="integer", nullable=true)
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AuthItem", mappedBy="ruleName")
     */
    private $items;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData( $data)
    {
        $this->data = $data;
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
    public function setCreatedAt( $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param int $updatedAt
     */
    public function setUpdatedAt( $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(ArrayCollection $items): void
    {
        $this->items = $items;
    }

}