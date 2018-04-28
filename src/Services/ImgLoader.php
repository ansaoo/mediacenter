<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 19/04/18
 * Time: 22:28
 */

namespace App\Services;


use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class ImgLoader
{
    private $target;
    private $datasourceExe;

    public function __construct($targetDir, $datasourceExe)
    {
        $this->target = $targetDir;
        $this->datasourceExe = $datasourceExe;
    }

    /**
     * @return mixed
     */
    public function getDatasourceExe()
    {
        return $this->datasourceExe;
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

    public function load($filename)
    {
        $command = $this->getDatasourceExe() . " --file $filename";
        file_put_contents(
            'logs/command',
            Yaml::dump(
                array(
                    md5(uniqid('cmd', true)) => array(
                        'eventDate' => date_create('now'),
                        'subject' => $filename,
                        'body' => $command
                    )
                )
            ),
            FILE_APPEND);
        $load = new Process($command);
        $load->run();
        if ($load->getOutput()) {
            file_put_contents(
                'logs/es_load_success',
                Yaml::dump(
                    array(
                        md5(uniqid('es', true)) => array(
                            'eventDate' => date_create('now'),
                            'subject' => $filename,
                            'body' => $load->getOutput()
                        )
                    )
                ),
                FILE_APPEND);
        } else {
            file_put_contents(
                'logs/es_load_error',
                Yaml::dump(
                    array(
                        md5(uniqid('es', true)) => array(
                            'eventDate' => date_create('now'),
                            'subject' => $filename,
                            'error' => true,
                            'body' => $load->getErrorOutput()
                        )
                    )
                ),
                FILE_APPEND);
        }
        return array(
            'filename' => $filename,
            'success' => $load->getOutput(),
            'error' => $load->getErrorOutput()
        );
    }
}