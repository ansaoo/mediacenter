<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/04/18
 * Time: 13:01
 */

namespace App\Controller;
header('Access-Control-Allow-Origin: *');

use App\Entity\YouTubeTask;
use App\Services\YouTubeDownloader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YoutubeDl\YoutubeDl;

class ApiController extends Controller
{
    public function index(Request $request, YouTubeDownloader $downloader)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        $user = $this->getUser();

        $app = $request->query->get('app') ?? null;
        switch ($app) {
            case 'extract':
                $result = $downloader->extract($request->query->get('filename'));
                return $this->json($result);
            case 'youtube':
                $task = new YouTubeTask();
                $task->setUrl($request->query->get('url'));
                $result = $downloader->download($task);
                return $this->json($result);
            case 'youtubedl':
                $dl = new YoutubeDl(array(
                    'continue' => false,
                    'format' => 'bestvideo+bestaudio',
//                    'extract-audio' => true,
//                    'audio-format' => 'm4a',
//                    'audio-quality' => 0, // best
                    'output' => '%(title)s.%(ext)s',
                ));
                $dl->setDownloadPath($this->getParameter('ddlPath'));
//                $dl->onProgress(function ($progress) {
//                    $percentage = $progress['percentage'];
//                    $size = $progress['size'];
//                    $speed = $progress['speed'] ?? null;
//                    $eta = $progress['eta'] ?? null;
//
//                    echo "Percentage: $percentage; Size: $size";
//                    if ($speed) {
//                        echo "; Speed: $speed";
//                    }
//                    if ($eta) {
//                        echo "; ETA: $eta";
//                    }
//                    // Will print: Percentage: 21.3%; Size: 4.69MiB; Speed: 4.47MiB/s; ETA: 00:01
//                });
                $video = $dl->download($request->query->get('url'));
                return $this->json($video->getFilename());
            default:
                return $this->json('API resources is only available via REST');
        }
    }
}