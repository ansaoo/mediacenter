<?php

namespace App\Controller;

use App\Entity\Carburant;
use App\Entity\Entretien;
use App\Form\CarburantTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarburantController extends Controller
{
    /**
     * @Route("/car/data/index", name="car_data_index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY',null,'Unable to access this page!');
        return $this->render('car/index.html.twig');
    }

    /**
     * @Route("/car/data/overview", name="car_data_overview")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function overview()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Unable to access this page');
        return $this->render('car/overview.html.twig');
    }

    /**
     * @Route("/car/data/fuel", name="car_data_fuel")
     */
    public function loadCarburant()
    {
        $repository = $this->getDoctrine()->getRepository(Carburant::class);
        $rows = $repository->findAll();
        return $this->json(array_map(function (Carburant $elem) {
            return $elem->_toArray();
        }, $rows));
    }

    /**
     * @Route("/car/data/fuel/new", name="car_data_fuel_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addCarburant(Request $request)
    {
        $fuel = new Carburant();
        $form = $this->createForm(CarburantTaskType::class, $fuel);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fuel);
            $entityManager->flush();
            return $this->json(array('save' => true));
        }
        return $this->json(array('save' => false));
    }

    /**
     * @Route("/car/data/maintains", name="car_data_maintains")
     */
    public function loadEntretien()
    {
        $repository = $this->getDoctrine()->getRepository(Entretien::class);
        $rows = $repository->findAll();
        return $this->json(array_map(function (Entretien $elem) {
            return $elem->_toArray();
        }, $rows));
    }

    /**
     * @Route("/car/data/maintains/new", name="car_data_maintains_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addEntretien(Request $request)
    {
        $maintain = new Entretien();
        $form = $this->createForm(CarburantTaskType::class, $maintain);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maintain);
            $entityManager->flush();
            return $this->json(array('save' => true));
        }
        return $this->json(array('save' => false));
    }
}
