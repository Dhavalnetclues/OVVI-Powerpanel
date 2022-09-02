<?php
namespace App\Helpers;

use App\Helpers\MyLibrary;
use Config;
use File;
use Validator;

class FileUploader
{
    public static function upload($request, $folder, $mimes = false, $required = false, $ignoreArray = [])
    {

        $response = false;
        $rules = [];
        $messages = [];
        $nbr = count($request['uploader']);

        if ($required) {
            $rules['uploader'] = 'required';
        }

        if ($nbr == 0) {
            $messages = ['uploader.required' => 'You must select at least one file before attempting to upload.'];
        }

        if ($nbr == 1) {
            $rules['uploader'] = 'file|max:20480';
            $rules['uploader'] .= (($mimes) ? '|mimes:' . $mimes : '');
            $messages['uploader.mimes'] = 'Invalid File';
            $messages['uploader.max'] = 'Maximum upload limit is 100 MB';
        } else {
            for ($i = 0; $i < $nbr; $i++) {
                $rules['uploader.' . $i] = 'file|max:20480';
                $rules['uploader.' . $i] .= ($mimes ? '|mimes:' . $mimes : '');
                $messages['uploader.' . $i . '.mimes'] = 'Invalid File ' . ($i + 1);
                $messages['uploader.' . $i . '.max'] = 'File ' . ($i + 1) . ': Maximum upload limit is 100 MB';
            }
        }

        $valid = Validator::make($request, $rules, $messages);

        if ($valid->passes()) {
            if ($nbr == 1) {
                if (!in_array($request['uploader']->getClientOriginalName(), $ignoreArray)) {
                    $response = Self::fupload($request['uploader'], $folder);
                    if (isset($response['errors'])) {
                        $response = ['errors' => implode(',', $response['errors']['uploader'])];
                    }
                }
            } else {
                foreach ($request['uploader'] as $key => $file) {
                    if (!in_array($file->getClientOriginalName(), $ignoreArray)) {
                        $response[$key] = Self::fupload($file, $folder, false);

                        if (isset($response[$key]['errors'])) {
                            $response = ['errors' => implode(',', $response[$key]['errors'])];
                        }

                    }
                }
            }
        } else {
            $response = ['errors' => $valid->errors()->messages()];
        }
        return $response;
    }

    public static function fupload($file, $folder, $sleep = false)
    {
        // if($sleep){
        //     sleep(1);
        // }

        $extension = $file->getClientOriginalExtension();
        $file_name = time() . '-' . explode('.' . $extension, MyLibrary::clean($file->getClientOriginalName()))[0];
        $fileOriginalName = explode('.' . $extension, $file->getClientOriginalName())[0];

        if (!is_dir(public_path('/' . $folder))) {
            File::makeDirectory(public_path('/' . $folder), 0755, true, true);
        }

        $filename = $file_name . '.' . $extension;
        $destinationPath = public_path('/' . $folder);
        $file->move($destinationPath, $filename);
        $path = $filename;

        $response = [
            'uploaded' => true,
            'url' => Config::get('Constant.CDN_PATH') . '/' . $folder,
            'preview' => $path,
            'file_name' => $file_name,
            'extension' => $extension,
            'file_original_name' => $fileOriginalName,
        ];
        return $response;
    }

}
