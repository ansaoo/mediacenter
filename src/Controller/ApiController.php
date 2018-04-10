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
                $video = $dl->download($request->query->get('url'));
                return $this->json($video->getFilename());
            default:
                return $this->json('API resources is only available via REST');
        }
    }
}