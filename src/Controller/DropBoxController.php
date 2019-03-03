<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 02/03/19
 * Time: 22:21
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DropBoxController extends Controller
{

    /**
     * @Route("/picture/todo", name="TODO")
     */
    public function sendToDropBox()
    {
        return $this->json(null);
    }
}