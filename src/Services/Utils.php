<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 22/04/18
 * Time: 13:13
 */

namespace App\Services;


class Utils
{
    static function human_filesize($size, $precision = 2) {
        static $units = array(' B',' kB',' MB',' GB',' TB',' PB',' EB',' ZB',' YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision).$units[$i];
    }
}