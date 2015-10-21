<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 06.10.15
 * Time: 18:36
 */

namespace AppBundle\Entity;

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
    protected $inGallery = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $onHomepage = true;

    public function __toString()
    {
        return $this->title ? $this->title : 'Фотография';
    }

    protected function getUploadDir()
    {
        return 'uploads/gallery';
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->filename ? $this->getUploadDir() . '/' . $this->filename : null;
    }

    public function getAbsolutePath()
    {
        return $this->filename !== null ? $this->getUploadRootDir() . '/' . $this->filename : null;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->filename)) {
            // store the old name to delete after the update
            $this->oldFilename = $this->filename;
            $this->filename = null;
        } else {
            $this->filename = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->getFile() !== null) {
            // do whatever you want to generate a unique name
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

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->filename);

        // check if we have an old image
        if ($this->oldFilename !== null && \is_file($this->getUploadRootDir() . '/' . $this->oldFilename)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->oldFilename);
            // clear the temp image path
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
}
