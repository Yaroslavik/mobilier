<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 21.10.15
 * Time: 15:28
 */

namespace AppBundle\Utils;

class ImageManager
{
    static public function createThumbnails($filename, $thumbSettings)
    {
        foreach ($thumbSettings as $thumbSetting) {
            static::createThumbnail($filename, $thumbSetting);
        }
    }

    static public function getThumbWebPath($filename, ThumbSetting $thumbSetting)
    {
        $pathParts = \pathinfo($filename);
        return $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' .
            $thumbSetting->getPostfix() . '.' . $thumbSetting->getExtension();
    }

    static public function createThumbnail($filename, ThumbSetting $thumbSetting, $dstFilename = null)
    {
        $srcImage = static::loadImage($filename);
        $srcWidth = \imagesx($srcImage);
        $srcHeight = \imagesy($srcImage);
        $dstWidth = $thumbSetting->getWidth();
        $dstHeight = $thumbSetting->getHeight();

        // Crop image
        $multiplier = \min($srcHeight / $dstHeight, $srcWidth / $dstWidth);
        $cropWidth = \floor($multiplier * $dstWidth);
        $cropHeight = \floor($multiplier * $dstHeight);
        $cropOffsetX = \floor(($srcWidth - $cropWidth) / 2);
        $cropOffsetY = \floor(($srcHeight - $cropHeight) / 2);
         \imagecopy($srcImage, $srcImage, 0, 0, $cropOffsetX, $cropOffsetY, $cropWidth, $cropHeight);

        // Resample image
        $dstImage = \imagecreatetruecolor($dstWidth, $dstHeight);
        \imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $dstWidth, $dstHeight, $cropWidth, $cropHeight);

        if (!$dstFilename) {
            $pathParts = \pathinfo($filename);
            $dstFilename = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' .
                $thumbSetting->getPostfix() . '.' . $thumbSetting->getExtension();
        }

        static::writeImage($dstImage, $dstFilename, $thumbSetting->getType());
    }

    static public function loadImage($filename)
    {
        if (!\is_readable($filename)) {
            throw new \Exception("Файл $filename недоступен для чтения.");
        }

        $imageParams = \getImageSize($filename);

        switch ($imageParams[2]) {
            case IMAGETYPE_GIF:
                $image = \imageCreateFromGif($filename);
                break;
            case IMAGETYPE_JPEG:
                $image = \imageCreateFromJpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $image = \imageCreateFromPng($filename);
                break;
            default:
                throw new \Exception("Неподдерживаемый формат изображения");
        }

        return $image;
    }

    static public function writeImage($image, $filename, $type, $quality = 80)
    {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return \imagejpeg($image, $filename, $quality);
            case IMAGETYPE_PNG:
                return \imagepng($image, $filename);
            case IMAGETYPE_GIF:
                return \imagegif($image, $filename);
        }
    }

    static public function deleteThumbs($filename)
    {
        $pathParts = \pathinfo($filename);
        $directory = new \DirectoryIterator($pathParts['dirname']);

        foreach ($directory as $file) {
            if (\strpos($file->getFilename(), $pathParts['filename']) === 0) {
                \unlink($file->getRealPath());
            }
        }
    }
}