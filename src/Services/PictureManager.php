<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 02/01/19
 * Time: 22:50
 */

namespace App\Services;


use App\Entity\Exif;
use App\Entity\MediaInfo;
use App\Entity\Picture;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class PictureManager
{
    private $em;
    private $exclude;
    private $scan;

    public function __construct(EntityManager $em, $scanFolder, $excludeFolder)
    {
        $this->em = $em;
        $this->exclude = explode("|", $excludeFolder);
        $this->scan = $scanFolder;
    }

    public function scan($folder, ObjectRepository $repository)
    {
        $cmd = "find $folder -type f -iname \"*.*\"";
        $find = new Process($cmd);
        $find->run();
        $files = explode("\n", $find->getOutput());
        $result = array_map(function ($filename) use ($repository) {
            $valid = array_reduce($this->exclude, function ($carry, $folder) use ($filename) {
                return mb_strpos($filename, $folder) ? false : $carry;
            }, true);
            if ($valid) {
                $found = $repository->findOneBy(["filename" => $filename]);
                if ($found) {
                    return false;
                }
                return $filename;
            }
            return false;
        }, $files);
        $result = array_filter($result);
        $count = 1;

        $scanId = date_create("now")->getTimestamp();
        file_put_contents("scans/$scanId.yml",Yaml::dump(array("files" => $files)));
        return array(
            "timestamp" => $scanId,
            "recordsTotal" => count($result),
            "data" => array_values(array_map(function ($elem) use (&$count) {
                $info = pathinfo($elem);
                $info["filename"] = $elem;
                $info["DT_RowId"] = $count;
                $count++;
                return $info;
            }, $result)),
            "scanId" => "$scanId.yml"
        );
    }

    public function add(Picture $new)
    {
        $this->em->persist($new);
        try {
            $this->em->flush();
        } catch (OptimisticLockException $e) {
            return array(
                "status" => 500,
                "state" => "failed",
                "msg" => "OptimisticLockException: {$e->getMessage()}"
            );
        }
        return array(
            "status" => 0,
            "state" => "success",
            "msg" => "Picture object '{$new->getName()}' saved."
        );
    }
}