<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/03/18
 * Time: 21:15
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    public function index($base, $filename)
    {
        /**
         * $basePath can be either exposed (typically inside web/)
         * or "internal"
         */
//        $basePath = $this->container->getParameter('imagePath');

        $filePath = $base.'/'.$filename;

        // check if file exists
        $fs = new FileSystem();
        if (!$fs->exists($filePath)) {
            throw $this->createNotFoundException();
        }

        // prepare BinaryFileResponse
        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
        return $response;
    }
}