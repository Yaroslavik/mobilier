<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 06.10.15
 * Time: 18:36
 */

namespace AppBundle\Entity;

use AppBundle\Utils\ImageManager;
use AppBundle\Utils\ThumbSetting;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="PortfolioItemRepository")
 * @ORM\Table(name="portfolio_item")
 * @ORM\HasLifecycleCallbacks
 */
class PortfolioItem
{
    const UPLOAD_DIR = '/uploads/gallery';

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description = '';

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $filename;

    protected $oldFilename;

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    protected $order = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible = true;

    /**
     * @ORM\ManyToOne(targetEntity="PortfolioCategory", inversedBy="items")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    protected $category;

    protected $thumbs = [
        'small' => [
            'width' => 500,
            'height' => 280,
            'type' => IMAGETYPE_JPEG,
            'quality' => null,
            'postfix' => null
        ]
    ];

    protected function getThumbSettings($name = null)
    {
        $thumbs = [];
        foreach ($this->thumbs as $key => $thumb) {
            $thumbs[$key] = new ThumbSetting(
                $thumb['width'],
                $thumb['height'],
                $thumb['type'],
                $thumb['quality'],
                $thumb['postfix']
            );
        }

        if ($name !== null) {
            return isset($thumbs[$name]) ? $thumbs[$name] : null;
        }

        return $thumbs;
    }

    public function __toString()
    {
        return $this->title ? $this->title : 'Фотография';
    }

    protected function getUploadRootDir()
    {
        return WEB_DIR . static::UPLOAD_DIR;
    }

    public function getWebPath()
    {
        return $this->filename ? static::UPLOAD_DIR . '/' . $this->filename : null;
    }

    public function getThumb($name)
    {
        $thumb = $this->getThumbSettings($name);
        return $thumb && $this->filename ? ImageManager::getThumbWebPath($this->getWebPath(), $thumb) : null;
    }

    public function getAbsolutePath()
    {
        return $this->filename !== null ? $this->getUploadRootDir() . '/' . $this->filename : null;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->filename)) {
            $this->oldFilename = $this->filename;
            $this->filename = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->getFile() !== null) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filename = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ($this->getFile() === null) {
            return;
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->filename);
        ImageManager::createThumbnails($this->getAbsolutePath(), $this->getThumbSettings());

        if ($this->oldFilename !== null && \is_file($this->getUploadRootDir() . '/' . $this->oldFilename)) {
            unlink($this->getUploadRootDir() . '/' . $this->oldFilename);
            ImageManager::deleteThumbs($this->getUploadRootDir() . '/' . $this->oldFilename);
            $this->oldFilename = null;
        }

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if (\is_file($file)) {
            unlink($file);
            ImageManager::deleteThumbs($file);
        }
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * Set filename
     *
     * @param string $filename
     *
     * @return PortfolioItem
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set inGallery
     *
     * @param boolean $inGallery
     *
     * @return PortfolioItem
     */
    public function setInGallery($inGallery)
    {
        $this->inGallery = $inGallery;

        return $this;
    }

    /**
     * Get inGallery
     *
     * @return boolean
     */
    public function getInGallery()
    {
        return $this->inGallery;
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
     * Set category
     *
     * @param \AppBundle\Entity\PortfolioCategory $category
     *
     * @return PortfolioItem
     */
    public function setCategory(\AppBundle\Entity\PortfolioCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\PortfolioCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
