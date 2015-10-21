<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 21.10.15
 * Time: 22:08
 */

namespace AppBundle\Utils;

class ThumbSetting
{
    const DEFAULT_JPEG_QUALITY = 80;

    protected $width;

    protected $height;

    protected $type;

    protected $quality;

    protected $postfix;

    public function __construct($width, $height, $type = IMAGETYPE_JPEG, $quality = null, $postfix = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        $this->quality = $quality ? : self::DEFAULT_JPEG_QUALITY;
        $this->postfix = $postfix ? : $width . 'x' . $height;
    }

    public function getExtension()
    {
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                return 'jpg';
            case IMAGETYPE_PNG:
                return 'png';
            case IMAGETYPE_GIF:
                return 'gif';
        }
    }

    /**
     * @return mixed
     */
    public function getPostfix()
    {
        return $this->postfix;
    }

    /**
     * @param mixed $postfix
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param mixed $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }
}