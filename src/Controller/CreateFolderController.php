<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 25/03/18
 * Time: 18:06
 */

namespace App\Controller;

use App\Document\Car;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateFolderController extends Controller
{
    public function mkdir(Request $request)
    {
        $folder = $request->request->get('folder') ?? null;

        if (!$folder) {
            return $this->json(array('error' => 'Folder name required'));
        }

        if (is_dir($this->getParameter('imagePath') . '/' . $folder)) {
            return $this->json(array('error' => 'dir_exist'));
        }
        mkdir($this->getParameter('imagePath') . '/' . $folder);
        return $this->json(array('done' => $folder));
    }

    public function create()
    {
        $car = new Car();
        $car->setMarque('Toyota');
        $car->setModel('Celica');
        $car->setCv(143);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($car);
        $dm->flush();

        return new Response('Created product id '.$car->getId());
    }


}