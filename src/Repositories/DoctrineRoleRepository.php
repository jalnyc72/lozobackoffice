<?php

namespace Digbang\Backoffice\Repositories;

use Digbang\Backoffice\Support\PaginatorFactory;
use Digbang\Security\Roles\DefaultRole;
use Digbang\Security\Roles\DoctrineRoleRepository as AbstractRoleRepository;
use Digbang\Security\Roles\Role;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrineRoleRepository extends AbstractRoleRepository
{
    /**
     * @var PaginatorFactory
     */
    private $paginatorFactory;

    /**
     * @param EntityManager    $entityManager
     * @param PaginatorFactory $paginatorFactory
     */
    public function __construct(EntityManager $entityManager, PaginatorFactory $paginatorFactory)
    {
        parent::__construct($entityManager);

        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * Get the entity name for this repository.
     * This entity MUST implement \Digbang\Security\Entities\Contracts\Role.
     *
     * @return string
     */
    protected function entityName()
    {
        return DefaultRole::class;
    }

    /**
     * @param string      $name
     * @param string|null $slug
     *
     * @return Role
     */
    protected function createRole($name, $slug = null)
    {
        return new DefaultRole($name, $slug);
    }

    /**
     * @param array $filters
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     *
     * @throws Mapping\MappingException
     *
     * @return array|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function search($filters = [], $orderBy = [], $limit = 10, $offset = 0)
    {
        $queryBuilder = $this->createQueryBuilder('r');

        if (isset($filters['name'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('r.name', ':name'));
            $queryBuilder->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (isset($filters['slug'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('r.slug', ':slug'));
            $queryBuilder->setParameter('slug', '%' . $filters['slug'] . '%');
        }

        if (isset($filters['permission'])) {
            $permissionClass = $this->getClassMetadata()->getAssociationMapping('permissions')['targetEntity'];
            $queryBuilder->andWhere($queryBuilder->expr()->exists(
                "SELECT 1 FROM $permissionClass p WHERE p.name LIKE :permission AND p.role = r.id"
            ));

            $queryBuilder->setParameter('permission', "%" . $filters['permission'] . "%");
        }

        if (!empty($orderBy)) {
            $queryBuilder->orderBy(key($orderBy), current($orderBy) ?: 'asc');
        }

        if ($limit !== null && $offset !== null) {
            $queryBuilder->setMaxResults($limit);
            $queryBuilder->setFirstResult($offset);

            return $this->paginatorFactory->fromDoctrinePaginator(
                new Paginator($queryBuilder)
            );
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
