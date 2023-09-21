<?php
namespace App\Helper;

use Intervention\Image\Facades\Image;

class ImageManager {

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
            ->save(public_path($path).$img_file_name, 50, 'webp');
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
}
