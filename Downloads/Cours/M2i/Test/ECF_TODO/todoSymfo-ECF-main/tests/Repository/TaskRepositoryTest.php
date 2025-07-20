<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em = null, ClassMetadata $classMetadata = null)
    {
        parent::__construct(
            $em ?? $registry->getManagerForClass(Task::class),
            $classMetadata ?? new ClassMetadata(Task::class)
        );
    }

    /**
     * Retourne les tâches selon leur état (faites ou non faites).
     *
     * @param bool $isDone
     * @return Task[]
     */
    public function findByIsDone(bool $isDone): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isDone = :done')
            ->setParameter('done', $isDone)
            ->getQuery()
            ->getResult();
    }
}
