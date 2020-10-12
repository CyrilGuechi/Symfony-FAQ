<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findByTag($tag)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.tags', 't')
            ->andWhere('t = :tag')
            ->andWhere('q.isBlocked = false')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Question[] Returns an array of Question objects
     */
    public function findAllToDeactivate(int $days)
    {
        return dd($this->createQueryBuilder('q')
            ->andWhere('q.active = true')
            ->andWhere('q.updatedAt < :datetime')
            ->setParameter('datetime', new \DateTime('-'. $days .' days'))
            ->getQuery())
            ->getResult()
        ;
    }
    
    public function deactivateOldQuestions()
    {
        /* 
        On aurait pu demander à MySQL de faire tout le travail de modificatio ndes question à la place de PHP avec cette requête :

        UPDATE question SET active = 0 WHERE updated_at < adddate(now(), interval -7 day) AND active = 1
        */

        return $this->getEntityManager()->createQuery('
                UPDATE App\Entity\Question q
                SET q.active = false
                WHERE q.updatedAt < date_sub(current_date(), 7, \'day\') AND q.active = true'
            )
            ->getResult()
        ;
    }
    
}
