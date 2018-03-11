<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/03/18
 * Time: 21:55
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends Controller
{
    public function index(Request $request, $filename)
    {
//        unlink($this->getParameter('publicPath').$filename);
        unlink($this->getParameter('publicPath').$request->query->get('file'));
        return $this->json(array('deleted' => $request->query->get('file')));
    }
}