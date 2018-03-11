<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/03/18
 * Time: 21:16
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageViewController extends Controller
{
    public function index()
    {
        return $this->render('images/index.html.twig', array(
            'folder' => null,
            'images' => null,
            'videos' => null
        ));
    }

    public function view($year,$month)
    {
        $images = glob($this->getParameter('imagePath')."$year-$month/*.JPG");
        $videos = glob($this->getParameter('imagePath')."$year-$month/*.MTS");
        return $this->render('images/index.html.twig', array(
            'folder' => "$year-$month/",
            'images' => array_map(function ($e) {
                return basename($e);
            }, $images),
            'videos' => array_map(function ($e) {
                return basename($e);
            }, $videos),
        ));
    }
}