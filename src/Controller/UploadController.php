<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 01/12/18
 * Time: 22:20
 */

namespace App\Controller;


use App\Entity\Upload;
use App\Entity\UploadTask;
use App\Entity\User;
use App\Form\UploadTaskType;
use App\Services\FileUploader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends Controller
{
    /**
     * Only upload async via ajax
     *
     * @Route("/upload/task",
     *     name="upload"
     * )
     * @param Request $request
     * @param FileUploader $uploader
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request, FileUploader $uploader)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(array(
                'error' => 'access_denied'
            ));
        }

        $task = new UploadTask();
        $form = $this->createForm(UploadTaskType::class, $task);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($request->files) {
                if ($request->files->has('upload_task')) {
                    $files = $request->files->get('upload_task')['file'];
                } else {
                    $files = array();
                }
                $files = is_array($files) ? $files : array($files);
                foreach ($files as $file) {
                    $uploader->upload($file);
                }
                return $this->json(array('success' => $request->files->all()));
            } else {
                return $this->json(array('error' => "no file submitted"));
            }
        }

        return $this->json(array(
            'error' => "no file submitted",
            'nota' => "only upload async via ajax"
        ));
    }


    /**
     * @Route("/upload/check",
     *     name="upload_check"
     * )
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadedBytes(Request $request, FileUploader $fileUploader)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(array(
                'error' => 'access_denied'
            ));
        }
        $filename = $request->get('file');
        if ($filename) {
            $info = pathinfo($filename);
            if (in_array(mb_strtolower($info['extension']), explode('|',$this->getParameter('upload_allowed')))) {
                $find = glob($fileUploader->getTargetDir() . '/' . $filename . '.*');
                $size = 0;
                foreach ($find as $file) {
                    $size += filesize($file);
                }
                return $this->json(array('file' => array('size' => $size)));
            } else {
                return $this->json(array('error' => $info['extension'].' extension is not allowed'));
            }
        }
        return $this->json(array('file' => null));
    }

    /**
     * @Route("/upload/merge",
     *     name="upload_merge"
     *     )
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function merge_file(Request $request, FileUploader $fileUploader)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(array(
                'error' => 'access_denied'
            ));
        }
        $loguser = $this->getUser();
        $filename = $request->get('file');
        $size = $request->get('size');
        $target = $request->get('target','upload_target');
        $cleanedName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $filename);
        if ($filename) {
            $info = pathinfo($filename);
            $find = glob($fileUploader->getTargetDir() .'/'. $filename .'.*');
            $files = implode("\" \"", $find);
            $cleanedName = $this->getParameter($target) .'/'. $cleanedName;
            $command = "cat \"$files\" > \"$cleanedName\"";
            $process = new Process($command);
            $process->run();
            foreach ($find as $files) {
                unlink($files);
            }
            if (filesize($cleanedName) == $size) {
                $user = new User();
                $user->setName($loguser->getUsername())
                    ->setPassword($loguser->getPassword())
                    ->setGranted("ROLE_USER")
                ;
                $upload = new Upload();
                $upload->setOriginalFilename($filename)
                    ->setFilename($cleanedName)
                    ->setFileSize($size)
                    ->setTarget($target)
                    ->setType($info["extension"])
                    ->setCreated(date_create("now"))
                    ->setUser($user)
                ;
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($upload);
                $manager->flush();
                return $this->json(array('success' => basename($cleanedName)));
            } else {
                unlink($cleanedName);
            }
        }
        return $this->json(array('fail' => null));
    }

    /**
     * @Route("/upload/data",
     *     name="upload_data"
     *     )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploads(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(array(
                'error' => 'access_denied'
            ));
        }
        $user = $this->getUser();
        $upload = $this->getDoctrine()->getRepository(Upload::class);
        $expr = new Comparison("name", Comparison::IS, $user->getUsername());
        $criteria = new Criteria();
        $criteria->where($expr);
        $result = $upload->findBy([$criteria]);
        return $this->json($result);
    }
}