<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * @template T
 * @template-extends ServiceEntityRepository<T>
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @return T
     *
     * @throws Exception
     */
    public function get(mixed $id)
    {
        $entity = $this->find($id);

        if (!$entity) {
            throw new Exception(sprintf('Entity "%s" with id "%s" not found.', $this->getClassName(), $id));
        }

        return $entity;
    }

    /**
     * @param T $entity
     *
     * @throws ORMException
     */
    public function persist(object $entity, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager();

        if (!$entityManager->contains($entity)) {
            $entityManager->persist($entity);
        }

        if ($flush) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->flush();
    }
}
