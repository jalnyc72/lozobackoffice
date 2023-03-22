<?php

namespace Digbang\Backoffice\Repositories;

use Digbang\Backoffice\Support\PaginatorFactory;
use Digbang\Security\Persistences\PersistenceRepository;
use Digbang\Security\Roles\RoleRepository;
use Digbang\Security\Users\DefaultUser;
use Digbang\Security\Users\DoctrineUserRepository as AbstractUserRepository;
use Digbang\Security\Users\User;
use Digbang\Security\Users\ValueObjects;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrineUserRepository extends AbstractUserRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginatorFactory;

    /**
     * @param EntityManager         $entityManager
     * @param PersistenceRepository $persistences
     * @param RoleRepository        $roles
     * @param PaginatorFactory      $paginatorFactory
     */
    public function __construct(EntityManager $entityManager, PersistenceRepository $persistences, RoleRepository $roles, PaginatorFactory $paginatorFactory)
    {
        parent::__construct($entityManager, $persistences, $roles);

        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * Get the User class name.
     *
     * @return string
     */
    protected function entityName()
    {
        return DefaultUser::class;
    }

    /**
     * Create a new user based on the given credentials.
     *
     * @param array $credentials
     *
     * @return User
     */
    protected function createUser(array $credentials)
    {
        if (count(array_only($credentials, ['email', 'password', 'username'])) < 3) {
            throw new \InvalidArgumentException("Missing arguments.");
        }

        $user = new DefaultUser(
            new ValueObjects\Email($credentials['email']),
            new ValueObjects\Password($credentials['password']),
            $credentials['username']
        );

        $rest = array_except($credentials, ['email', 'username', 'password']);
        if (!empty($rest)) {
            $user->update($rest);
        }

        return $user;
    }

    public function search(array $filters, array $orderBy = [], $limit = 10, $offset = 0)
    {
        $queryBuilder = $this->createQueryBuilder('u');

        $this->parseFilters($filters, $queryBuilder);

        if (!empty($orderBy)) {
            $queryBuilder->orderBy(key($orderBy), current($orderBy) ?: 'asc');
        }

        if ($limit !== null && $offset !== null) {
            $queryBuilder
                ->setMaxResults($limit)
                ->setFirstResult($offset);

            return $this->paginatorFactory->fromDoctrinePaginator(
                new Paginator($queryBuilder)
            );
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array $filters
     */
    private function parseFilters(array $filters, QueryBuilder $queryBuilder)
    {
        $filters = array_filter($filters, function ($field) {
            return $field !== null && $field !== '';
        });

        if (array_key_exists('login', $filters)) {
            $queryBuilder->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('u.email.address', ':login'),
                $queryBuilder->expr()->eq('u.username', ':login')
            ));

            $queryBuilder->setParameter('login', $filters['login']);
        }

        if (array_key_exists('email', $filters)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.email.address', ':email'));
            $queryBuilder->setParameter('email', '%' . $filters['email'] . '%');
        }

        if (array_key_exists('username', $filters)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.username', ':username'));
            $queryBuilder->setParameter('username', '%' . $filters['username'] . '%');
        }

        if (array_key_exists('firstName', $filters)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.name.firstName', ':firstName'));
            $queryBuilder->setParameter('firstName', '%' . $filters['firstName'] . '%');
        }

        if (array_key_exists('lastName', $filters)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('u.name.lastName', ':lastName'));
            $queryBuilder->setParameter('lastName', '%' . $filters['lastName'] . '%');
        }

        if (array_key_exists('activated', $filters)) {
            $queryBuilder->leftJoin('u.activations', 'a');

            if ($filters['activated']) {
                $queryBuilder->andWhere('a.completed = true');
            }
        }
    }
}
