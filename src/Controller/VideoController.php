<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 22/05/18
 * Time: 22:19
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends Controller
{
    public function movies(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        $user = $this->getUser();
        return $this->render('video/movies.html.twig', array(
            'menu' => array(
                'video' => array(
                    'li' => 'active',
                    'ul' => null,
                    'movie' => 'active'
                )
            )
        ));
    }
}