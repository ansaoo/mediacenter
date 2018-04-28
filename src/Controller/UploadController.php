<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/02/18
 * Time: 23:49
 */

namespace App\Controller;


use App\Entity\UploadTask;
use App\Form\UploadTaskType;
use App\Services\FileUploader;
use App\Services\ImgLoader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class UploadController extends Controller
{
    public function index(Request $request, FileUploader $fileUploader)
    {
        $task = new UploadTask();
        $form = $this->createForm(UploadTaskType::class, $task);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($request->files) {
                if ($request->files->get('upload_task')) {
                    $files = $request->files->get('upload_task')['file'];
                } else {
                    $files = array();
                }
                $files = is_array($files) ? $files : array($files);
                foreach ($files as $file) {
                    $fileUploader->upload($file);
                }
                return $this->json(array('success' => $request->files->all()));
            } else {
                return $this->json(array('error' => 'null'));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($task->getFile()) {
                return $this->json(array(
                    'test' => $task->getFile()
                ));
            } else {
                return $this->json(array('test' => 'no file'));
            }
        }

        return $this->render('upload/new.html.twig', array(
            'form' => $form->createView(),
            'files' => null,
        ));
    }

    /**
     * @Route("//verif_file",
     *     name="verif_file"
     * )
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadedBytes(Request $request, FileUploader $fileUploader)
    {
        $filename = $request->query ? $request->query->get('file') : null;
        $maxChunkSize = $request->query->get('maxChunkSize') ? $request->query->get('maxChunkSize') : 0;
        if ($filename) {
            $find = glob($fileUploader->getTargetDir() .'/'. $filename .'.*');
            $size = 0;
            foreach ($find as $file) {
                $size += filesize($file);
            }
            return $this->json(array('file' => array('size' => $size)));
        }
        return $this->json(array('file' => null));
    }

    /**
     * @Route("/merge_file",
     *     name="merge_file"
     *     )
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function merge_file(Request $request, FileUploader $fileUploader, ImgLoader $imgLoader)
    {
        $count = 0;
        $_type = $request->query->get('type') ?? 'uploadPath';
        $filename = $request->query->get('file') ?? null;
        $size = $request->query->get('size') ?? null;
        $cleanedName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $filename);
        if ($filename) {
            $find = glob($fileUploader->getTargetDir() . '/' . $filename . '.*');
            $count = count($find);
            $files = implode("\" \"", $find);
            $cleanedName = $this->getParameter($_type) . '/' . $cleanedName;
            $command = "cat \"$files\" > \"$cleanedName\"";
            $process = new Process($command);
            $process->run();
            foreach ($find as $files) {
                unlink($files);
            }
            if (filesize($cleanedName) == $size) {
                if ($_type == 'image_path') {
                    if ($imgLoader->check($cleanedName)) {
                        $cleanedName = $imgLoader->rename($cleanedName);
                        $response = $imgLoader->load($cleanedName);
                        return $this->json($response);
                    }
                }
                return $this->json(array('success' => basename($cleanedName)));
            } else {
                unlink($cleanedName);
                return $this->json(array('error' => basename($cleanedName)));
            }
        }
        return $this->json('fail');
    }
}