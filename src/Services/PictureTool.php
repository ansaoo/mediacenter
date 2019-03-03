<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 26/01/19
 * Time: 21:51
 */

namespace App\Services;


use App\Entity\Exif;
use App\Entity\MediaInfo;
use App\Entity\Picture;

class PictureTool
{
    public function __construct()
    {
    }

    public function exif2(Picture $picture)
    {
        return new Exif();
    }

    public function mediainfo(Picture $picture)
    {
        return new MediaInfo();
    }
}