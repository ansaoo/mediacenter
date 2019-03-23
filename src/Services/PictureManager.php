<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 02/01/19
 * Time: 22:50
 */

namespace App\Services;


use App\Entity\Exif;
use App\Entity\Picture;
use App\Entity\Scan;
use App\Services\NanoPhotosProvider\Gallery;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use function GuzzleHttp\Psr7\mimetype_from_filename;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class PictureManager
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var array
     */
    private $exclude;
    /**
     * @var Gallery
     */
    private $gallery;
    /**
     * @var string
     */
    private $root;
    /**
     * @var string
     */
    private $scanPath;
    /**
     * @var PictureTool
     */
    private $tools;

    public function __construct(LoggerInterface $logger, EntityManager $em, Gallery $gallery, PictureTool $tool, string $rootFolder, string $scanHistoryFolder, $excludeFolder)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->exclude = explode("|", $excludeFolder);
        $this->gallery = $gallery;
        $this->root = $rootFolder .'/';
        $this->scanPath = $scanHistoryFolder;
        $this->tools = $tool;
    }

    public function scan(ObjectRepository $repository)
    {
        $cmd = "find {$this->root} -type f -iname \"*.*\"";
        $find = new Process($cmd);
        $find->run();
        $files = explode("\n", $find->getOutput());
        $result = array_map(function ($filename) use ($repository) {
            $valid = array_reduce($this->exclude, function ($carry, $folder) use ($filename) {
                return mb_strpos($filename, $folder) ? false : $carry;
            }, true);
            if ($valid) {
                $filename = str_replace($this->root, "", $filename);
                $found = $repository->findOneBy(["filename" => $filename]);
                if ($found) {
                    return false;
                }
                return $filename;
            }
            return false;
        }, $files);
        $result = array_values(array_filter($result));

        $scan = (new Scan())
            ->setRefId(date_create("now")->getTimestamp())
            ->setCreated(date_create("now"))
            ->setStatus("pending")
            ->setTotalFiles(count($result))
            ->setIsFinish(false)
        ;
        file_put_contents("{$this->scanPath}/{$scan->getRefId()}.yml",Yaml::dump(array("files" => $result)));
        try {
            $this->em->persist($scan);
            $this->em->flush();
        } catch (OptimisticLockException | ORMException $e) {
            $this->logger->error($e->getMessage());
            $error = $e->getMessage();
        }
        $count = 1;
        return array(
            "timestamp" => $scan->getRefId(),
            "recordsTotal" => $scan->getTotalFiles(),
            "data" => array_map(function ($elem) use (&$count) {
                $info = pathinfo($this->root . $elem);
                $info["filename"] = $elem;
                $info["DT_RowId"] = $count;
                $count++;
                return $info;
            }, $result),
            "scanId" => $scan->getRefId(),
            "error" => $error ?? null
        );
    }

    /**
     * @param Scan $scan
     * @return array
     */
    public function loadScan(Scan $scan)
    {
        $error = array();
        $this->gallery->setTnSize(array(
            "hxs" => "auto",
            "wxs" => 250,
            "hsm" => "auto",
            "wsm" => 250,
            "hme" => "auto",
            "wme" => 250,
            "hla" => "auto",
            "wla" => 250,
            "hxl" => "auto",
            "wxl" => 250));
        $files = Yaml::parseFile("{$this->scanPath}/{$scan->getRefId()}.yml");
        $count = 0;
        foreach ($files["files"] as $filename) {
            set_time_limit(20);
            $count++;
            try {
                $now = date_create("now");
                $fullFilename = $this->root . $filename;
                $name = basename($filename);
                $metadata = @exif_read_data($fullFilename, 0, true);
                $metaFile = $metadata["FILE"];
                $exif = $this->tools->exif2(array_replace(
                    $metadata["COMPUTED"] ?? array(),
                    $metadata["IFD0"] ?? array(),
                    $metadata["EXIF"] ?? array()
                ));
                $createdDateTime = $exif instanceof Exif && $exif->getCreatedDateTime() ?
                    $exif->getCreatedDateTime() :
                    $this->tools->findDateTimeRegex($fullFilename);

                if ($createdDateTime == null) {
                    $statFile = stat($fullFilename);
                    $createdDateTime = date_create("now");
                    $createdDateTime->setTimestamp($statFile["mtime"]);
                }
                if ($metaFile == null) {
                    $metaFile = $statFile ?? stat($fullFilename);
                }

                $new = new Picture();
                $new->setFilename($filename)
                    ->setOriginalFilename($fullFilename)
                    ->setName($name)
                    ->setStatus(true)
                    ->setAdded($now)
                    ->setFileSize(($metaFile["FileSize"] ?? $metaFile["size"]) / 1024 / 1024)
                    ->setCreated($createdDateTime)
                    ->setType($metaFile["MimeType"] ?? mime_content_type($fullFilename))
                    ->setExif($exif)
                    ->setMediainfo($this->tools->mediainfo($new))
                    ->setGalleryItem($this->gallery->prepareData($new->getFilename(), "IMAGE"));

                $this->em->persist($new);
                $scan->setProgress($count);
                $scan->setPercent(100 * $count / $scan->getTotalFiles());
                $this->em->persist($scan);
                $this->em->flush();
            } catch (\Exception $e) {
                $this->logger->error("$filename => {$e->getMessage()}");
                $error[] = array($filename => $e->getMessage());
            }
        }
        if (empty($error)) {
            $scan->setIsFinish(true);
            try {
                $this->em->flush();
            } catch (OptimisticLockException | ORMException $e) {
                $this->logger->error($e->getMessage());
            }
            return array(
                "status" => 0,
                "state" => "success",
                "msg" => "Scan {$scan->getRefId()} completed successfully."
            );
        }
        else {
            $this->logger->error(json_encode($error));
            return array(
                "status" => 500,
                "state" => "failed",
                "msg" => "Some error has occurred",
                "error" => $error
            );
        }
    }

    public function add(Picture $new)
    {
        try {
            $this->em->persist($new);
            $this->em->flush();
            return array(
                "status" => 0,
                "state" => "success",
                "msg" => "Picture object '{$new->getName()}' saved."
            );
        } catch (OptimisticLockException | ORMException $e) {
            return array(
                "status" => 500,
                "state" => "failed",
                "msg" => "OptimisticLockException: {$e->getMessage()}"
            );
        }
    }
}