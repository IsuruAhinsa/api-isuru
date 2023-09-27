<?php

namespace App\Helper;

use Intervention\Image\Facades\Image;

class ImageManager
{
    public const NO_IMAGE = 'images/no-img.png';

    /**
     * @param string $name
     * @param int $width
     * @param int $height
     * @param string $path
     * @param string $file
     * @return string
     */
    final public static function uploadImage(string $name, int $width, int $height, string $path, string $file): string
    {
        $img_file_name = $name . ".webp";
        Image::make($file)
            ->fit($width, $height)
            ->save(public_path($path) . $img_file_name, 50, 'webp');
        return $img_file_name;
    }

    /**
     * @param string $path
     * @param string $img
     * @return void
     */
    final public static function deleteImage(string $path, string $img): void
    {
        $path = public_path($path) . $img;
        if ($img != '' && file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param string $path
     * @param string|null $img
     * @return string
     */
    final public static function prepareImageUrl(string $path, string|null $img): string
    {
        $url = url($path . $img);

        if (empty($img)) {
            $url = url(self::NO_IMAGE);
        }

        return $url;
    }

    /**
     * @param string $file
     * @param string $name
     * @param string $img_upload_path
     * @param string $thumb_img_upload_path
     * @return string
     */
    final public static function imageUploadProcess(string $file, string $name, string $img_upload_path, string $thumb_img_upload_path): string
    {
        $photo_name = self::uploadImage($name, 800, 800, $img_upload_path, $file);

        self::uploadImage($name, 150, 150, $thumb_img_upload_path, $file);

        return $photo_name;
    }

    /**
     * @param string $photo
     * @param string $img_upload_path
     * @param string $thumb_img_upload_path
     * @return void
     */
    final public static function deleteImageWhenExist(string $photo, string $img_upload_path, string $thumb_img_upload_path): void
    {
        if (!empty($photo)) {
            self::deleteImage($img_upload_path, $photo);
            self::deleteImage($thumb_img_upload_path, $photo);
        }
    }
}
