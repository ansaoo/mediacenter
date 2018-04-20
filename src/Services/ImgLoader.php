<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 19/04/18
 * Time: 22:28
 */

namespace App\Services;


use Symfony\Component\Process\Process;

class ImgLoader
{
    private $target;

    public function __construct($targetDir)
    {
        $this->target = $targetDir;
    }

    public function check($filename)
    {
        $check = new Process('exiv2 -g Exif.Photo.DateTimeOriginal '.$filename);
        $check->run();
        return $check->getOutput() ?? null;
    }

    public function rename($filename)
    {
        $rename = new Process('exiv2 -v -r %Y-%m-%d_%Hh%Mm%S_:basename: '.$filename);
        $rename->run();
        $response = $rename->getOutput();
        if ($response) {
            $response = explode("\n", $response)[1];
            $response = explode(' ', $response);
            return end($response);
        } else {
            return $filename;
        }
    }
}