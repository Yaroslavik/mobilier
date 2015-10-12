<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 12.10.15
 * Time: 19:57
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getActualComments($count = 10)
    {
        return $this->createQueryBuilder('comment')
            ->where('comment.visible = 1')
            ->orderBy('comment.order')
            ->setMaxResults($count)
            ->getQuery()
            ->execute();
    }
}