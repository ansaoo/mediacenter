<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/04/18
 * Time: 13:22
 */

namespace App\Services;


use App\Entity\YouTubeTask;
use Symfony\Component\Process\Process;

class YouTubeDownloader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function download(YouTubeTask $task)
    {
        $base = 'youtube-dl '.$task->getUrl();
        $processTitle = new Process($base. ' -e');
        $processTitle->run();
//        $title = $processTitle->getOutput();
//        $command = $base.' --audio-quality 0 -f "mp4" -o "'.
//            $this->getTargetDir().'/%(title)s.%(ext)s" '.
//            '--exec "ffmpeg -vn -acodec copy \''.
//            $this->getTargetDir().'/'.$title.'.m4a\' -i"';
//        $process = new Process($command);
//        $process->setTimeout(1800);
//        $process->run();

        return array(
            'error' => $processTitle->getErrorOutput(),
            'success' => $processTitle->getOutput()
        );
    }

    public function extract($filename)
    {
        $outname = substr($filename, 0, -4);
        $command = "ffmpeg -i \"". $this->getTargetDir() .
            "/$filename\" -vn -acodec aac \"".
            $this->getTargetDir() ."/$outname.m4a\"";
        file_put_contents('test', $command);
        $process = new Process($command);
        $process->run();

        return array(
            'error' => $process->getErrorOutput(),
            'success' => $process->getOutput(),
            'filename' => "$outname.m4a"
        );
    }

}