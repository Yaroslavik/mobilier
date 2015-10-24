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
            ->leftJoin('portfolio_category.items', 'item', 'WITH', 'item.visible = 1')
            ->where('portfolio_category.visible = 1')
            ->addOrderBy('item.order')
            ->getQuery()
            ->execute();
    }
}