<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 06.03.18
 * Time: 12:05
 */

namespace KotaShade\DoctrineRbac;

use common\modules\EA\Entity\Organization\Organization\Member;
use yii\rbac\Assignment;
use yii\rbac\DbManager as BaseManager;
use KotaShade\DoctrineRbac\Entity as EntityNS;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\base\Component;
use Yii;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use Doctrine\ORM\EntityManager;

abstract class DoctrineDbManager extends Component implements ManagerInterface
{

    public $dbResourceName = 'doctrine';
    /** @var EntityManager */
    protected $em;

    protected $itemRepo;

    public function init()
    {
        parent::init();
        /** @var \KotaShade\doctrine\components\DoctrineComponent $doctrine */
        $doctrine = Yii::$app->get($this->dbResourceName);
        $em = $doctrine->getEntityManager();
        $this->setEntityManager($em);
    }

    public function createRole($name)
    {
        $roleE = new EntityNS\AuthItemRole();
        $roleE->setName($name);
        return $roleE;
    }

    public function createPermission($name)
    {
        $permissionE = new EntityNS\AuthItemPermission();
        $permissionE->setName($name);
        return $permissionE;
    }

    public function add($object)
    {
        if ($object instanceof EntityNS\AuthItem) {
            $this->getEntityManager()->persist($object);
        } elseif ($object instanceof Rule) {
            $this->getEntityManager()->persist($object);
        }
        throw new InvalidArgumentException('Adding unsupported object type.');
    }

    public function remove($object)
    {
        // TODO: Implement remove() method.
    }

    public function update($name, $object)
    {
        // TODO: Implement update() method.
    }

    public function getRole($name)
    {
        // TODO: Implement getRole() method.
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getRolesByUser($userId)
    {
        // TODO: Implement getRolesByUser() method.
    }

    public function getChildRoles($roleName)
    {
        // TODO: Implement getChildRoles() method.
    }

    public function getPermission($name)
    {
        // TODO: Implement getPermission() method.
    }

    public function getPermissions()
    {
        // TODO: Implement getPermissions() method.
    }

    public function getPermissionsByRole($roleName)
    {
        // TODO: Implement getPermissionsByRole() method.
    }

    public function getPermissionsByUser($userId)
    {
        // TODO: Implement getPermissionsByUser() method.
    }

    public function getRule($name)
    {
        /** @var EntityNS\AuthRule $ruleE */
        if (($ruleE = $this->getEntityManager()->find(EntityNS\AuthRule::class, $name)) == null) {
            return null;
        }

        $data = $ruleE->getData();
        $data = Yii::getAlias($data);
        return Yii::createObject($data);
    }

    /**
     * @return array|Rule[]
     */
    public function getRules()
    {
        $repo = $this->getEntityManager()->getRepository(EntityNS\AuthRule::class);
        $res = $repo->findAll();

        $rules = [];
        /** @var EntityNS\AuthRule $ruleE */
        foreach($res as $ruleE) {
            $rules[$ruleE->getName()] = $ruleE;
        }
        return $rules;
    }

    public function canAddChild($parent, $child)
    {
        return !$this->detectLoop($parent, $child);
    }

    /**
     * Checks whether there is a loop in the authorization item hierarchy.
     * @param Item $parent the parent item
     * @param Item $child the child item to be added to the hierarchy
     * @return bool whether a loop exists
     */
    protected function detectLoop($parent, $child)
    {
        //TODO реализовать проверку зацикливания
        return true;
    }

    /**
     * @param EntityNS\AuthItem $parent
     * @param EntityNS\AuthItem $child
     * @return bool
     */
    public function addChild($parent, $child)
    {
        if ($parent->getName() === $child->getName()) {
            throw new InvalidArgumentException("Cannot add '{$parent->getName()}' as a child of itself.");
        }

        if ($parent instanceof EntityNS\AuthItemPermission && $child instanceof EntityNS\AuthItemRole) {
            throw new InvalidArgumentException('Cannot add a role as a child of a permission.');
        }

        if ($this->detectLoop($parent, $child)) {
            throw new InvalidCallException("Cannot add '{$child->getName()}' as a child of '{$parent->getName()}'. A loop has been detected.");
        }

        $aicE = new EntityNS\AuthItemChild();
        $aicE->setChild($child);
        $aicE->setParent($parent);
        $this->getEntityManager()->persist($aicE);

        return true;
    }

    public function removeChild($parent, $child)
    {
        $repo = $this->getEntityManager()->getRepository(EntityNS\AuthItemChild::class);
        $list = $repo->findBy(['parent' => $parent, 'child' => $child]);
        /** @var EntityNS\AuthItemChild $aicE */
        foreach($list as $aicE) {
            $this->getEntityManager()->remove($aicE);
        }

        return true;
    }

    public function removeChildren($parent)
    {
        $repo = $this->getEntityManager()->getRepository(EntityNS\AuthItemChild::class);
        $list = $repo->findBy(['parent' => $parent]);
        /** @var EntityNS\AuthItemChild $aicE */
        foreach($list as $aicE) {
            $this->getEntityManager()->remove($aicE);
        }
        return true;
    }

    /**
     * @param EntityNS\AuthItem $parentE
     * @param EntityNS\AuthItem $childE
     * @return bool
     */
    public function hasChild($parentE, $childE)
    {
        $repo = $this->getEntityManager()->getRepository(EntityNS\AuthItemChild::class);
        $list = $repo->findBy(['parent' => $parentE, 'child' => $childE]);
        return (count($list) > 0);
    }

    public function getChildren($name)
    {
        /** @var EntityNS\AuthItem $itemE */
        $itemE = $this->getEntityManager()->find(EntityNS\AuthItem::class, $name);
        $list = $itemE->getChildren();
        /** @var EntityNS\AuthItemChild $aicE */
        foreach($list as $aicE) {
            $childE = $aicE->getChild();
            $children[$childE->getName()] = $childE;
        }
        return $children;
    }

    abstract public function assign($role, $userE);


    public function revoke($role, $userId)
    {
        // TODO: Implement revoke() method.
    }

    public function revokeAll($userId)
    {
        // TODO: Implement revokeAll() method.
    }

    public function getAssignment($roleName, $userId)
    {
        // TODO: Implement getAssignment() method.
    }

    abstract public function getAssignments($user);

    public function getUserIdsByRole($roleName)
    {
        // TODO: Implement getUserIdsByRole() method.
    }

    public function removeAll()
    {
        // TODO: Implement removeAll() method.
    }

    public function removeAllPermissions()
    {
        // TODO: Implement removeAllPermissions() method.
    }

    public function removeAllRoles()
    {
        // TODO: Implement removeAllRoles() method.
    }

    public function removeAllRules()
    {
        // TODO: Implement removeAllRules() method.
    }

    public function removeAllAssignments()
    {
        // TODO: Implement removeAllAssignments() method.
    }

    public function checkAccess($userId, $permissionName, $params = [])
    {
        // TODO: Implement checkAccess() method.
        $assignments = $this->getAssignments($userId);
        if (count($assignments) == 0) {
            return false;
        }

        $itemRepo = $this->getItemRepo();
        if (($itemE = $itemRepo->find($permissionName)) == null) {
            return false;
        }
        /** @var \common\components\AuthUserComponent $authUser */
        $authUser = Yii::$app->get('user');
        $identityModel = $authUser->getIdentity();
        $identityE = $identityModel->getIdentityEntity();

        return $this->checkAccessRecursive($identityE, $itemE, $params, $assignments);
    }

    public function checkAccessRecursive($identityE, EntityNS\AuthItem $itemE, $params, $assignments)
    {

        Yii::debug(($itemE instanceof EntityNS\AuthItemRole ? "Checking role: ".$itemE->getName()
            : "Checking permission: " . $itemE->getName()) .", __METHOD__");

        if (!$this->executeRule($identityE, $itemE, $params)) {
            return false;
        }

        $itemName = $itemE->getName();

        if (isset($assignments[$itemName])) {
            return true;
        }

        $parentList = $itemE->getParents();
        /** @var EntityNS\AuthItemChild $aicE */
        foreach ($parentList as $aicE) {
            $parentItemE = $aicE->getParent();
            if ($this->checkAccessRecursive($identityE, $parentItemE, $params, $assignments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $identityE
     * @param $item
     * @param $params
     * @return bool
     */
    private function executeRule($identityE, $itemE, $params)
    {
        //FIXME не реализовано
        return true;
    }

    /**
     * Возвращает репозиторий AuthItem энтити
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getItemRepo()
    {
        if ($this->itemRepo == null) {
            $em = $this->getEntityManager();
            $this->itemRepo = $em->getRepository(EntityNS\AuthItem::class);
        }
        return $this->itemRepo;
    }

    /**
     * @return string
     */
    public function getDbResourceName(): string
    {
        return $this->dbResourceName;
    }

    /**
     * @param string $dbResourceName
     */
    public function setDbResourceName(string $dbResourceName): void
    {
        $this->dbResourceName = $dbResourceName;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     * @return self
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }


}