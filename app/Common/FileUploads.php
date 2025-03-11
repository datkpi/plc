<?php

namespace App\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploads
{
    public function uploadFile($request, $data, $name, $inputName = 'files')
    {
        $requestFile = $request->file($inputName);
        try {
            $dir = 'public/files/' . $name;
            $fixName = $data->id . '-' . $name . '.' . $requestFile->extension();

            if ($requestFile) {
                Storage::putFileAs($dir, $requestFile, $fixName);
                $request->file = 'files/' . $name . '/' . $fixName;

                $data->update([
                    $inputName => $request->file,
                ]);
            }

            return true;
        } catch (\Throwable $th) {
            report($th);

            return $th->getMessage();
        }
    }

    // delete file
    public function deleteFile($fileName = 'files')
    {
        try {
            if ($fileName) {
                Storage::delete('public/photos/shares/' . $fileName);
            }

            return true;
        } catch (\Throwable $th) {
            report($th);
            return $th->getMessage();
        }
    }

     // delete file if exist
     public static function deleteIfExistImage($folder, $fileName = 'files')
     {
         try {
             $dir = $fileName;
             $dir = str_replace('storage', 'public', $dir);
             if (Storage::exists($dir)) {
                 Storage::delete($dir);
             }

         } catch (\Throwable $th) {
             report($th);
             return $th->getMessage();
         }
     }


    /**
     * For Upload Images.
     * @param mixed $request
     * @param mixed $data
     * @param mixed $name
     * @param mixed|null $inputName
     * @return bool|string
     */
    public static function uploadImage(Request $request, $folder, $inputName = 'image', $oldFileName = '')
    {

        $requestFile = $request->file($inputName);
        try {
            $dir = 'public/photos/shares/' . $folder;
            $url = 'storage/photos/shares/'. $folder;
            $fixName = uniqid(). '.' . $requestFile->extension();
            $upload = Storage::putFileAs($dir, $requestFile, $fixName);
            // /storage/photos/shares/avatars/64b9f25e27ef1.png
            // "public/photos/shares/avatars"
            if($oldFileName != '' && $oldFileName!= null && $upload)
            {
               self::deleteIfExistImage($folder, $oldFileName);
            }
            return $url.'/'.$fixName;

        } catch (\Throwable $th) {
            report($th);
            return $th->getMessage();
        }
    }

    // public function uploadAvatar($request, $inputName = 'avatar')
    // {
    //     $requestFile = $request->file($inputName);
    //     try {
    //         // $name = Str::random(25);
    //         $dir = 'storage/photos/shares/avatars/' . $name;
    //         $fixName = uniqid() . '.' . $requestFile->extension();

    //         if ($requestFile) {
    //             Storage::putFileAs($dir, $requestFile, $fixName);
    //             $request->image = $fixName;

    //             $data->update([
    //                 $inputName => $request->image,
    //             ]);
    //         }

    //         return true;
    //     } catch (\Throwable $th) {
    //         report($th);

    //         return $th->getMessage();
    //     }
    // }

    public function uploadPhoto($request, $data, $name)
    {
        try {
            $dir = 'public/photos/' . $name;
            $fixName = $data->id . '-' . $name . '.' . $request->file('photo')->extension();

            if ($request->file('photo')) {
                Storage::putFileAs($dir, $request->file('photo'), $fixName);
                $request->photo = $fixName;

                $data->update([
                    'photo' => $request->photo,
                ]);
            }
        } catch (\Throwable $th) {
            report($th);

            return $th->getMessage();
        }
    }
}
