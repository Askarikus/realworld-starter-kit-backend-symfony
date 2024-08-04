<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method find($id, $lockMode = null, $lockVersion = null)
 * @method findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method findOneBy(array $criteria, ?array $orderBy = null)
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entityClass());
    }

    /**
     * @return class-string<object>
     */
    abstract public function entityClass(): string;

    /**
     * @phpstan-ignore-next-line
     */
    public function search(string $column, string $query): array
    {
        $result = $this->createQueryBuilder('t')
            ->where("t.$column LIKE :query")
            ->setParameter('query', "%$query%")
            ->getQuery()
            ->getResult();

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }
}
