<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 24.10.15
 * Time: 21:27
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PortfolioCategoryRepository extends EntityRepository
{
    public function getVisibleCategories()
    {
        return $this->createQueryBuilder('portfolio_category')
            ->where('portfolio_category.visible = 1')
            ->addOrderBy('portfolio_category.order')
            ->getQuery()
            ->execute();
    }
}