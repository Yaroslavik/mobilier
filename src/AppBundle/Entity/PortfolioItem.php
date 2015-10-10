<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 06.10.15
 * Time: 18:36
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="portfolio_item")
 */
class PortfolioItem
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $description = '';

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    protected $order = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $onHomepage = true;

    public function __toString()
    {
        return $this->title ? $this->title : 'Фотография';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PortfolioItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PortfolioItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return PortfolioItem
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return PortfolioItem
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set onHomepage
     *
     * @param boolean $onHomepage
     *
     * @return PortfolioItem
     */
    public function setOnHomepage($onHomepage)
    {
        $this->onHomepage = $onHomepage;

        return $this;
    }

    /**
     * Get onHomepage
     *
     * @return boolean
     */
    public function getOnHomepage()
    {
        return $this->onHomepage;
    }
}
