<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/03/18
 * Time: 21:16
 */

namespace App\Controller;

use App\Entity\ImgSearchTask;
use App\Form\ImgSearchTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

class ImageViewController extends Controller
{
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        $user = $this->getUser();

        $task = new ImgSearchTask();
        $form = $this->createForm(ImgSearchTaskType::class, $task);
        $form->handleRequest($request);

        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);
        $menu = array(
            'book' => null,
            'video' => array(
                'li' => null,
                'ul' => 'collapse',
                'movie' => null,
                'tvshow' => null
            ),
            'audio' => array(
                'li' => null,
                'ul' => 'collapse',
                'album' => null,
                'performer' => null,
                'kind' => null,
                'youtube' => null
            ),
            'image' => 'active',
            'game' => null,
            'car' => array(
                'li' => null,
                'ul' => 'collapse',
                'data' => null,
                'overview' => null
            )
        );

        if ($request->query->has('tag')) {
            $keyword = array(
                'tag' => $request->query->get('tag')
            );
            $method = 'search/image';
        } else {
            $keyword = array();
            $method = 'recent/image';
        }

        if ($form->isSubmitted()) {
            $keyword['gte'] = $task->getFromDate();
            $keyword['lt'] = $task->getToDate();
            return $this->render('images/index.html.twig', array(
                'form' => $form->createView(),
                'title' => $task->getKeyword() ?? $task->getFromDate().' to '.$task->getToDate(),
                'api_url' => $match[0] ?? null,
                'method' => 'search/image',
                'keyword' => $keyword,
                'menu' => $menu,
                'user' => $user
            ));
        }

        return $this->render('images/index.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Récents',
            'api_url' => $match[0] ?? null,
            'method' => $method,
            'keyword' => $keyword,
            'menu' => $menu,
            'user' => $user
        ));
    }

    public function sendToDrop(Request $request, $filename)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        $user = $this->getUser();
        $command = "cp $filename";
        $copy = new Process($command);

    }
}