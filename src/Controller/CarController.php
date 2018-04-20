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
            'api_url' => $match[0] ?? null
        ));
    }

    public function data(Request $request)
    {
        $ref = $request->headers->get('referer');
        preg_match('/(.*):8000*/', $ref, $match);
        return $this->render('car/data.html.twig', array(
            'api_url' => $match[0] ?? null
        ));
    }
}