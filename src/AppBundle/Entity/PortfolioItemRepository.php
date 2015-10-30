<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 14.10.15
 * Time: 2:50
 */

namespace AppBundle\Entity;

use Gedmo\Sortable\Entity\Repository\SortableRepository;

class PortfolioItemRepository extends SortableRepository
{
    public function getHomepageItems($count = 12)
    {
        return $this->createQueryBuilder('portfolio_item')
            ->where('portfolio_item.visible = 1')
            ->andWhere('portfolio_item.category is null')
            ->orderBy('portfolio_item.order')
            ->setMaxResults($count)
            ->getQuery()
            ->execute();
    }

    public function getItemsByCategory($category, $limit = null)
    {
        $qb = $this->getBySortableGroupsQueryBuilder(['category' => $category]);
        return $qb->andWhere('n.visible = 1')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }
}