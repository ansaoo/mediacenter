<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 09/04/18
 * Time: 17:27
 */

namespace App\Controller;


use App\Entity\YouTubeTask;
use App\Form\YouTubeTaskType;
use App\Services\YouTubeDownloader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class YouTubeController extends Controller
{
    public function index(Request $request, YouTubeDownloader $downloader)
    {
        $task = new YouTubeTask();
        $form = $this->createForm(YouTubeTaskType::class, $task);
        $form->handleRequest($request);

        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('music/youtube.html.twig', array(
                'form' => $form->createView(),
                'title' => $downloader->download($task),
                'url' => $task->getUrl(),
                'api_url' => $match[0] ?? null
            ));
        }

        return $this->render('music/youtube.html.twig', array(
            'form' => $form->createView(),
            'title' => null,
            'url' => null,
            'api_url' => $match[0] ?? null
        ));
    }
}