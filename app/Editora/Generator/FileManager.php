<?php

namespace App\Editora\Generator;
use Str;

class FileManager
{

    public static function get ($path)
    {
        return file_get_contents($path);
    }

    public static function save ($path, $data)
    {
        file_put_contents($path, $data);
    }

    public static function removeOldFiles ($path, $startingPattern=null)
    {
        $files=scandir($path);
        foreach ($files as $file) {
            if ($file!='.' && $file!='..') {
                if ($startingPattern==null || Str::of($file)->startsWith($startingPattern)) {
                    $fullFilePath="$path$file";
                    echo "Deleting $fullFilePath file \n";
                    self::deleteFiles($fullFilePath);
                }
            }
        }       
    }

    public static function deleteFiles($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            
            foreach( $files as $file ){
                self::deleteFiles( $file );      
            }
            if (file_exists($target)) {
                rmdir( $target );
            }
            
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }

    public static function createDiretoryStructure ($path)
    {
        $fail=false;
        $arr = explode('\\', $path);
        $j=0;
        for ($i=0; $i < count($arr); $i++) {
            if ($arr[$i]) {
                $new_arr[$j++] = $arr[$i];
            }
        }
        //---------------------------------------------//

        $arr = $new_arr;
        $end = count($arr);

        $path="";
        for ($i=0; $i < $end; $i++) {
            if ($path=="") {
                    $path=$arr[$i];
            } else {
                    $path .= "\\".$arr[$i];
            }
            if (!file_exists($path)) {
                if (!@mkdir($path, 0777)) {
                    $fail = true;
                    break;
                }
            }
        }

        if ($fail) {
            return false;
        } else {
            return true;
        }
    }
}