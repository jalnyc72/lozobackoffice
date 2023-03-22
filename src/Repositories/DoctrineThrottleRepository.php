<?php

namespace Digbang\Backoffice\Repositories;

use Digbang\Security\Throttling\DoctrineThrottleRepository as AbstractThrottleRepository;
use Digbang\Security\Throttling\Throttle;
use Digbang\Security\Users\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\ORM\NoResultException;

class DoctrineThrottleRepository extends AbstractThrottleRepository
{
    /**
     * @var ExpressionBuilder
     */
    private $expr;

    /**
     * Throttling status.
     *
     * @var bool
     */
    private $enabled = true;

    /**
     * Finds a throttler by the given user ID.
     *
     * @param UserInterface $user
     * @param string        $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUser(UserInterface $user, $ipAddress = null)
    {
        $criteria = (new Criteria)
            ->where($this->expr->eq('user', $user));

        if ($ipAddress) {
            $criteria->andWhere($this->orIpAddressCriteria($ipAddress));
        }

        $queryBuilder = $this->createQueryBuilder('t')->addCriteria($criteria)->setMaxResults(1);

        try {
            $throttle = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            $entityName = $this->entityName;
            $throttle = $entityName::create($user, $ipAddress);

            $this->save($throttle);
        }

        return $this->throttle($throttle);
    }

    /**
     * Finds a throttler by the given user ID.
     *
     * @param int    $id
     * @param string $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUserId($id, $ipAddress = null)
    {
        $user = $this->userProvider->findById($id);

        return $this->findByUser($user, $ipAddress);
    }

    /**
     * Finds a throttling interface by the given user login.
     *
     * @param string $login
     * @param string $ipAddress
     *
     * @return ThrottleInterface
     */
    public function findByUserLogin($login, $ipAddress = null)
    {
        $user = $this->userProvider->findByLogin($login);

        return $this->findByUser($user, $ipAddress);
    }

    /**
     * Enable throttling.
     *
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;
    }

    /**
     * Disable throttling.
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Check if throttling is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    public function save(ThrottleInterface $throttle)
    {
        $em = $this->getEntityManager();

        $em->persist($throttle);
        $em->flush($throttle);
    }

    /**
     * @param $ipAddress
     *
     * @return \Doctrine\Common\Collections\Expr\CompositeExpression
     */
    private function orIpAddressCriteria($ipAddress)
    {
        return $this->expr->orX(
            $this->expr->eq('ipAddress', $ipAddress),
            $this->expr->isNull('ipAddress')
        );
    }

    private function throttle(ThrottleInterface $throttle)
    {
        if ($throttle instanceof RepositoryAware) {
            $throttle->setRepository($this);
        }

        return $throttle;
    }

    /**
     * Get the FQCN of each Throttle type:
     *   - null:     Base throttle type (eg: Digbang\Security\Throttling\DefaultThrottle)
     *   - 'global': Global throttle type (eg: Digbang\Security\Throttling\DefaultGlobalThrottle)
     *   - 'ip':     Ip throttle type (eg: Digbang\Security\Throttling\DefaultIpThrottle)
     *   - 'user':   User throttle type (eg: Digbang\Security\Throttling\DefaultUserThrottle).
     *
     * @param string|null $type
     *
     * @return string
     */
    protected function entityName($type = null)
    {
        // TODO: Implement entityName() method.
    }

    /**
     * Create a GlobalThrottle object.
     *
     * @return Throttle
     */
    protected function createGlobalThrottle()
    {
        // TODO: Implement createGlobalThrottle() method.
    }

    /**
     * Create an IpThrottle object.
     *
     * @param string $ipAddress
     *
     * @return Throttle
     */
    protected function createIpThrottle($ipAddress)
    {
        // TODO: Implement createIpThrottle() method.
    }

    /**
     * Create a UserThrottle object.
     *
     * @param User $user
     *
     * @return Throttle
     */
    protected function createUserThrottle(User $user)
    {
        // TODO: Implement createUserThrottle() method.
    }
}
