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
        $folder = glob($this->getParameter('imagePath').'/*-*');
        $images = glob($this->getParameter('imagePath')."/TEMP/*_thumb.jpg");
        return $this->render('images/index.html.twig', array(
            'folder' => array_map(function ($e) {
                return basename($e);
            }, $folder),
            'images' => array_map(function ($e) {
                return basename($e);
            }, $images),
            'videos' => null
        ));
    }

    public function view($year,$month)
    {
        $images = glob($this->getParameter('imagePath')."/$year-$month/*_thumb.jpg");
        $videos = glob($this->getParameter('imagePath')."/$year-$month/*.MP4");
        return $this->render('images/view.html.twig', array(
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