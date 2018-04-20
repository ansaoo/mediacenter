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

class ImageViewController extends Controller
{
    public function index(Request $request)
    {
        $task = new ImgSearchTask();
        $form = $this->createForm(ImgSearchTaskType::class, $task);
        $form->handleRequest($request);

        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('images/index.html.twig', array(
                'form' => $form->createView(),
                'title' => $task->getKeyword()->format('Y-m-d'),
                'api_url' => $match[0] ?? null,
                'method' => 'search/image',
                'keyword' => array(
                    'gte' => $task->getKeyword()->format('Y-m-d'),
                    'lt' => $task->getKeyword()->add(new \DateInterval('P1M'))->format('Y-m-d'),
                )
            ));
        }

        return $this->render('images/index.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Récents',
            'api_url' => $match[0] ?? null,
            'method' => 'recent/image',
            'keyword' => array()
        ));
    }
}