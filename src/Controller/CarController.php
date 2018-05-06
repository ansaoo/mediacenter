<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 12/04/18
 * Time: 17:44
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CarController extends Controller
{
    public function overview(Request $request)
    {
        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);
        return $this->render('car/overview.html.twig', array(
            'api_url' => $match[0] ?? null,
            'menu' => array(
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
                'image' => null,
                'game' => null,
                'car' => array(
                    'li' => 'active',
                    'ul' => null,
                    'data' => null,
                    'overview' => 'active'
                )
            )
        ));
    }

    public function data(Request $request)
    {
        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);
        return $this->render('car/data.html.twig', array(
            'api_url' => $match[0] ?? null,
            'menu' => array(
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
                'image' => null,
                'game' => null,
                'car' => array(
                    'li' => 'active',
                    'ul' => null,
                    'data' => 'active',
                    'overview' => null
                )
            )
        ));
    }

    public function add(Request $request)
    {

    }
}