<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MultimediaHelper{
    public static  function upload_image(string $dir, string $format, $image = null,$image_name=null)
    {
        if ($image != null) {
            $imageName =$image_name?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }
    public static function get_image_url(string $dir,$image){
        $path=null;
        if (!is_null($image) && Storage::disk('public')->exists($dir.'/' . $image)) {
            // $path = asset('public/storage/'.$dir.'/' . $image);
            $path = asset('storage/'.$dir.'/' . $image);
        }
        return $path;
    }

    public static function get_file_size(string $dir,$fileName){
        $size=null;
        if (!is_null($fileName) && Storage::disk('public')->exists($dir.'/' . $fileName)) {
            $size=Storage::disk('public')->size($dir . '/' . $fileName);
        }
        return $size;
    }

    public static function get_file_path(string $dir,$fileName){
        $path=null;
        if (!is_null($fileName) && Storage::disk('public')->exists($dir.'/' . $fileName)) {
            $path=$dir . '/' . $fileName;
        }
        return $path;
    }
    public static function  update_image(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = self::upload_image($dir, $format, $image);
        return $imageName;
    }
   public static function delete_image($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }
}
