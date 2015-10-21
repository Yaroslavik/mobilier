<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 14.10.15
 * Time: 2:50
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PortfolioItemRepository extends EntityRepository
{
    public function getHomepageItems($count = 12)
    {
        return $this->createQueryBuilder('portfolio_item')
            ->where('portfolio_item.onHomepage = 1')
            ->orderBy('portfolio_item.order')
            ->setMaxResults($count)
            ->getQuery()
            ->execute();
    }

    public function getGalleryItems()
    {
        return $this->createQueryBuilder('portfolio_item')
            ->where('portfolio_item.inGallery = 1')
            ->orderBy('portfolio_item.order')
            ->getQuery()
            ->execute();
    }
}