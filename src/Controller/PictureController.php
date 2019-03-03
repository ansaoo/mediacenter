<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Services\NanoPhotosProvider\Gallery;
use App\Services\PictureManager;
use App\Services\PictureTool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends Controller
{
    /**
     * @Route("/picture/spotlight", name="picture")
     */
    public function index()
    {
        return $this->render('picture/index.html.twig', [
            'menu' => array('image' => array(
                'li' => 'active',
                'ul' => '',
                'spotlight' => 'active'
            )),
        ]);
    }

    /**
     * @Route("/picture/provider", name="picture_provider")
     * @param Gallery $gallery
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function provider(Gallery $gallery, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        $pictures = $repository->findAll();
//        return $this->json(array(
//            "nano_status" => "ok",
//            "nano_message" => "",
//            "album_content" => array_map(function (Picture $elem) {
//                return $elem->toNanoGallery();
//            }, $pictures)
//        ));
        return $this->json($gallery->retrieve($request, $pictures));
    }

    /**
     * @Route("/picture/manager", name="picture_manager")
     */
    public function manager()
    {
        return $this->render('picture/manager.html.twig', [
            'menu' => array('image' => array(
                'li' => 'active',
                'ul' => '',
                'manager' => 'active'
            )),
        ]);
    }

    /**
     * @Route("/picture/scan", name="picture_scan")
     * @param Request $request
     * @param PictureManager $pictureManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function scanLibrary(Request $request, PictureManager $pictureManager)
    {
        if ($request->get("async")) {
            return $this->json(array(
                'draw' => $request->get("draw", 1),
                'data' => array(),
                'recordsTotal' => 0
            ));
        }
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        $result = $pictureManager->scan($repository);
        $result['draw'] = $request->get("draw", 1);
        return $this->json($result);
    }

    /**
     * @Route("/picture/add", name="picture_add")
     * @param Request $request
     * @param PictureManager $pictureManager
     * @param PictureTool $tool
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addPicture(Request $request, PictureManager $pictureManager, PictureTool $tool)
    {
        $root = $this->getParameter("image_root") . "/";
        $filename = $request->get("filename");
        if (!file_exists($root.$filename)) {
            return array(
                "status" => 404,
                "state" => "failed",
                "msg" => "'{$filename}' not found."
            );
        }
        $stat = stat($root.$filename);
        $info = pathinfo($root.$filename);
        $ctime = date_create("now");
        $ctime->setTimestamp($stat["ctime"]);
        $name = basename($filename);
        $new = new Picture();
        $new->setFilename($filename)
            ->setFileSize($stat["size"])
            ->setCreated($ctime)
            ->setOriginalFilename($root.$filename)
            ->setName($name)
            ->setStatus(true)
            ->setAdded(date_create("now"))
        ;
        $new->setExif($tool->exif2($new));
        $new->setMediainfo($tool->mediainfo($new));
        $result = $pictureManager->add($new);
        return $this->json($result);
    }

    /**
     * @Route("/picture/exist", name="picture_exist")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function existPicture(Request $request)
    {
        $filename = $request->get("filename");
        if (!file_exists($this->getParameter("image_root").'/'.$filename)) {
            return array(
                "status" => 404,
                "state" => "failed",
                "msg" => "'{$filename}' not found."
            );
        }
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        return $this->json(
            $repository->findOneBy(["filename" => $filename]) ? true : false
        );
    }

}
