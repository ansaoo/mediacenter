<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 01/12/18
 * Time: 22:23
 */

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
//        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $part = 1;
        $fileName = $file->getClientOriginalName() .sprintf(".%04d",$part);
        while (file_exists($this->getTargetDir() .'/'. $fileName)) {
            $part++;
            $fileName = $file->getClientOriginalName() . sprintf(".%04d", $part);
        }
        $file->move($this->getTargetDir(), $fileName);
        return $this->getTargetDir() .'/'. $fileName;
    }

    public function getFilePath(UploadedFile $file)
    {
        return $this->getTargetDir() .'/'. $file->getClientOriginalName();
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}