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
use Symfony\Component\HttpFoundation\Response;
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
        if ($base == 'img') {
            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->json(array(
                    'error' => 'access_denied'
                ), Response::HTTP_FORBIDDEN);
            }
            if (mb_strpos($filename, '_thumb',0,'utf-8') !== false) {
                $base = $this->getParameter('image_thumb_path') . '/' . substr($filename, 0, 7);
                $filePath = $base.'/'.$filename;
            } elseif (mb_strpos($filename, 'image_tmp_path',0,'utf-8') !== false) {
                $filePath = str_replace('image_tmp_path',$this->getParameter('image_tmp_path').'/',$filename);;
            } else {
                $base = $this->getParameter('image_path') .'/'. substr($filename, 10, 4) .'/'. substr($filename, 10, 7);
                $filePath = str_replace('image_path',$base.'/',$filename);
            }
            file_put_contents('test',$base);
        } else {
            $filePath = $base . '/' . $filename;
        }

        // check if file exists
        $fs = new FileSystem();
        if (!$fs->exists($filePath)) {
            throw $this->createNotFoundException();
        }

        // prepare BinaryFileResponse
        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
        return $response;
    }
}