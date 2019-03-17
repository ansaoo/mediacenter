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
use \JsonMapper;
use Psr\Log\LoggerInterface;

class PictureTool
{
    private $logger;
    private $mapper;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $mapper = new JsonMapper();
        $mapper->bExceptionOnUndefinedProperty = false;
        $mapper->bExceptionOnMissingData = true;
        $this->mapper = $mapper;
    }

    /**
     * @param $pExif
     * @return null|Exif|object
     */
    public function exif2($pExif)
    {
        try {
            $exif = json_encode(is_array($pExif) ? $pExif : exif_read_data($pExif,0,true), JSON_PARTIAL_OUTPUT_ON_ERROR);
            return $this->mapper->map(json_decode($exif), new Exif());
        } catch (\Exception $e) {
            $this->logger->error("PictureTool->exif2():".$e->getMessage());
            return null;
        }
    }

    /**
     * @param string $filename
     * @return \DateTime|null
     */
    public function findDateTimeRegex(string $filename)
    {
        try {
            preg_match("/(?P<year>\\d{4})-(?P<month>\\d{2})-(?P<day>\\d{2})_(?P<hour>\\d{2})h(?P<min>\\d{2})m(?P<sec>\\d{2})/", basename($filename), $matches);
            preg_match("/(?P<year>\\d{4})(?P<month>\\d{2})(?P<day>\\d{2})_(?P<hour>\\d{2})(?P<min>\\d{2})(?P<sec>\\d{2})/", basename($filename), $matches2);
            if ($matches) {
                $date = date_create(
                    sprintf(
                        "%s-%s-%s %s:%s:%s",
                        $matches["year"],
                        $matches["month"],
                        $matches["day"],
                        $matches["hour"],
                        $matches["min"],
                        $matches["sec"]
                    ));
                return $date instanceOf \DateTime ? $date : null;
            }
            if ($matches2) {
                $date = date_create(
                    sprintf(
                        "%s-%s-%s %s:%s:%s",
                        $matches2["year"],
                        $matches2["month"],
                        $matches2["day"],
                        $matches2["hour"],
                        $matches2["min"],
                        $matches2["sec"]
                    ));
                return $date instanceOf \DateTime ? $date : null;
            }
            $this->logger->debug("PictureTool->findDateTimeRegex($filename):".json_encode($matches)." ".json_encode($matches2));
            return null;
        } catch (\Exception $e) {
            $this->logger->error("PictureTool->findDateTimeRegex($filename):".$e->getMessage());
            return null;
        }
    }

    public function mediainfo(Picture $picture)
    {
        return new MediaInfo();
    }
}