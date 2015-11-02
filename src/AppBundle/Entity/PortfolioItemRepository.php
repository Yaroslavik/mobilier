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
    public function getItemsByCategory($category, $limit = null)
    {
        $qb = $this->getBySortableGroupsQueryBuilder(['category' => $category]);
        return $qb->andWhere('n.visible = 1')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }
}