<?php

namespace App\Logic\Image;

use App\Utils\Common;
use App\Utils\Constant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

class ImageRepository
{
    public function uploadOrderImage($file, $filename = '')
    {
        if ($filename == '') {
            $originalName = $file->getClientOriginalName();
            $filename = substr($originalName, 0, strlen($originalName) - 4);
        }

        $filename = $this->sanitize($filename);
        $filename = Common::convertNonVietnamese($filename);
        $filename .= '.jpg';

        $manager = new ImageManager();
        $folderImageOrder = public_path(Constant::FOLDER_IMAGE_ORDER);
        $image = $manager->make($file)->encode('jpg')->save($folderImageOrder . $filename);
        $image->destroy();

        return [
            'error' => false,
            'code'  => 200,
            'filename' => $filename,
        ];
    }

    public function uploadMultipleFiles($files)
    {
        foreach ($files as $file) {
            $validator = Validator::make(array('file' => $file), Image::$rules, Image::$messages);

            if ($validator->fails()) {
                return json_encode([
                    'error' => true,
                    'message' => $validator->messages()->first(),
                    'code' => 400
                ], 400);
            }

            $photo = $file;
            $filesize = $photo->getClientSize();
            $originalName = $photo->getClientOriginalName();
            $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - 4);

            $filename = $this->sanitize($originalNameWithoutExt);
            $filename = Common::convertNonVietnamese($filename);
            $allowed_filename = $this->createUniqueFilename( $filename , $obj_id);

            $filenameExt = $allowed_filename .'.jpg';
        }

        return json_encode([
            'error' => false,
            'code'  => 200
        ], 200);
    }

    function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
}
