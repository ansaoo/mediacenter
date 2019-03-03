<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 02/03/19
 * Time: 15:16
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends Controller
{
    /**
     * @Route("/download/{filename}", name="download",
     *      requirements={
     *          "filename": ".*"
     *      },
     *      defaults={
     *          "filename": "null"
     *      })
     * @param $filename
     * @return BinaryFileResponse
     */
    public function index($filename)
    {
        // check if file exists
        $fs = new Filesystem();
        if (!$fs->exists($filename)) {
            throw $this->createNotFoundException();
        }

        // prepare BinaryFileResponse
        $response = new BinaryFileResponse($filename);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            basename($filename),
            iconv('UTF-8', 'ASCII//TRANSLIT', basename($filename))
        );
        return $response;
    }
}