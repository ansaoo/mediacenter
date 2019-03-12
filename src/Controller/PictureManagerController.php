<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Scan;
use App\Services\PictureManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureManagerController extends Controller
{
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
     * @Route("/picture/scan/load",
     *     name="picture_scan_load")
     * @param Request $request
     * @param PictureManager $pictureManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loadScan(Request $request, PictureManager $pictureManager)
    {
        $ref = $request->get("ref", 0);
        $scan = $this->getDoctrine()
            ->getRepository(Scan::class)
            ->findOneBy(["refId" => $ref]);
        if ($scan instanceof Scan) {
            $pictureManager->loadScan($scan);
            return $this->json("ok");
        }
        return $this->json("Scan $ref not found");
    }

    /**
     * @Route("/picture/scan/lookup",
     *     name="picture_scan_stat")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function lookupScan(Request $request)
    {
        $ref = $request->get("ref", 0);
        $scan = $this->getDoctrine()
            ->getRepository(Scan::class)
            ->findOneBy(["refId" => $ref]);
        return $this->json($scan instanceof Scan ? $scan->toArray() : array("progress"=>0));
    }

}
