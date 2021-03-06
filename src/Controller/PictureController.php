<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureTaskType;
use App\Repository\PictureRepository;
use App\Services\NanoPhotosProvider\Gallery;
use App\Services\PictureManager;
use App\Services\PictureTool;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use http\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends Controller
{
    /**
     * @Route("/picture/spotlight/{year}/{month}",
     *     name="picture",
     *     requirements={"year": "\d{4}", "month": "\d{2}"},
     *     defaults={"year": "8888", "month": "88"}
     * )
     * @param int $year
     * @param int $month
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $year, int $month)
    {
        return $this->render('picture/index.html.twig', [
            'focus' => $year != 8888 && $month != 88 ?
                array("year" => $year, "month" => $month)
                : null,
            'menu' => array('image' => array(
                'li' => 'active',
                'ul' => '',
                'spotlight' => 'active'
            )),
        ]);
    }

    /**
     * @Route("/picture/provider/{year}/{month}",
     *      name="picture_provider",
     *      requirements={"year": "\d{4}", "month": "\d{1}|\d{2}"},
     *      defaults={"year": "8888", "month": "88"}
     * )
     * @param Gallery $gallery
     * @param Request $request
     * @param int $year
     * @param int $month
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function provider(Gallery $gallery, Request $request, int $year, int $month)
    {
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        if (!$repository instanceof PictureRepository) {
            throw new RuntimeException("failed_to_load_picture_repository");
        }
        if ($year != 8888 && $month != 88) {
            $pictures = $repository->findByYearAndMonth($year, $month);
        } else {
            $pictures = $repository->findBy(
                ["status" => true],
                ["created" => "desc"],
                50
            );
        }
        return $this->json(array(
            "nano_status" => "ok",
            "nano_message" => "",
            "album_content" => array_map(function (Picture $elem) {
                return $elem->toNanoGallery();
            }, $pictures)
        ));
    }

    /**
     * @Route("/picture/add", name="picture_add")
     * @param Request $request
     * @param PictureManager $pictureManager
     * @param PictureTool $tool
     * @param Gallery $gallery
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request, PictureManager $pictureManager, PictureTool $tool, Gallery $gallery)
    {
        $root = $this->getParameter("image_root") . "/";
        $filename = $request->get("filename");
        if (!file_exists($root . $filename)) {
            return array(
                "status" => 404,
                "state" => "failed",
                "msg" => "'{$filename}' not found."
            );
        }
        $stat = stat($root . $filename);
        $info = pathinfo($root . $filename);
        $ctime = date_create("now");
        $ctime->setTimestamp($stat["ctime"]);
        $name = basename($filename);
        $new = new Picture();
        $new->setFilename($filename)
            ->setFileSize($stat["size"])
            ->setCreated($ctime)
            ->setOriginalFilename($root . $filename)
            ->setName($name)
            ->setStatus(true)
            ->setAdded(date_create("now"))
            ->setExif($tool->exif2($new))
            ->setMediainfo($tool->mediainfo($new))
        ;
        $gallery->setTnSize(array(
            "hxs" => "auto",
            "wxs" => 250,
            "hsm" => "auto",
            "wsm" => 250,
            "hme" => "auto",
            "wme" => 250,
            "hla" => "auto",
            "wla" => 250,
            "hxl" => "auto",
            "wxl" => 250));
        $new->setGalleryItem($gallery->prepareData($new->getFilename(), "IMAGE"));
        $result = $pictureManager->add($new);
        return $this->json($result);
    }

    /**
     * @Route("/picture/edit", name="picture_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request)
    {
        $pictureId = $request->get("id");
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        $picture = $repository->find($pictureId);

        $form = $this->createForm(PictureTaskType::class, $picture);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($picture);
            $entityManager->flush();
            return $this->json(array('save' => true));
        }
        return $this->render("picture/form-update.html.twig");
    }

    /**
     * @Route("/picture/exist", name="picture_exist")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function exist(Request $request)
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

    /**
     * @Route("/picture/remove", name="picture_remove")
     * @param Request $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function remove(Request $request, LoggerInterface $logger)
    {
        $title = $request->get("title");
        $picture = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findOneBy(["title" => $title]);
        if ($picture instanceof Picture) {
            try {
                $picture->setStatus(false);
                $this->getDoctrine()->getManager()->persist($picture);
                $this->getDoctrine()->getManager()->flush();
                return $this->json(array("deleted" => true));
            } catch (OptimisticLockException $e) {
                $logger->error($e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->json(array(
            "deleted" => false,
            "error" => $error ?? "$title not found"
        ));
    }

    /**
     * @Route("/picture/metric", name="picture_metric")
     */
    public function metric()
    {
        $stat = array();
        $repository = $this->getDoctrine()->getRepository(Picture::class);
        if ($repository instanceof PictureRepository) {
            $stat["total_element"] = $repository->count([]);
            try {
                $stat["total_size"] = $repository->sumFileSize();
            } catch (NonUniqueResultException $e) {

            }
        }
        return $this->json($stat);
    }
}
