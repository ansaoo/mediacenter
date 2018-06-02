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
use Symfony\Component\Yaml\Yaml;

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
                'title' => $task->getKeyword() ??
                    date_create($task->getFromDate())->format('D, j F Y').' to '.date_create($task->getToDate())->format('D, j F Y'),
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

    public function sendToDrop(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        $user = $this->getUser();
        $fileSrc = $request->get('src');
        $filename = $request->get('title');
        if (mb_strpos($fileSrc, 'image_tmp_path',0,'utf-8') !== false) {
            $filePath = $this->getParameter('image_tmp_path').'/'.$filename;
        } else {
            $filePath = $this->getParameter('image_path').'/'.
                substr($filename, 0, 4).'/'.
                substr($filename, 0, 7).'/'.$filename;
        }
        $filePath = str_replace('&#x3D;','=',$filePath);
        $filename = str_replace('&#x3D;','=',$filename);
        $command = "cp $filePath ".$this->getParameter('dropbox_path')."/$filename";
        file_put_contents(
            'logs/command',
            Yaml::dump(
                array(
                    md5(uniqid('copy', true)) => array(
                        'eventDate' => date_create('now')->format('Y-m-d\TH:i:s.vO'),
                        'subject' => $filename,
                        'cmd' => $command
                    )
                )
            ),
            FILE_APPEND);
        $copy = new Process($command);
        $copy->run();
        return $this->json(array(
            'success' => 'Success',
            'error' => $copy->getErrorOutput()
        ));
    }
}