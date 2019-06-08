<?php

namespace App\Controller;

use App\Entity\Carburant;
use App\Entity\Entretien;
use App\Entity\Voiture;
use App\Form\CarburantTaskType;
use App\Form\EntretienTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarDataController extends Controller
{
    /**
     * @Route(
     *      "/car/follow/{kind}",
     *      name="car_follow",
     *      requirements={
     *          "kind": "fuel|maintain"
     *      },
     *      defaults={
     *          "kind": "fuel"
     *      })
     * @param string $kind
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(string $kind)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Unable to access this page!');
        return $this->render('car/index.html.twig', array(
            'kind' => explode("|", "fuel|maintain"),
            'tab' => $kind,
            'menu' => array(
                'car' => array(
                    'li' => 'active',
                    'ul' => '',
                    'data' => 'active'
                ))
        ));
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
     * @Route("/car/info", name="car_info")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getVoiture(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Voiture::class);
        $found = $repository->find($request->get("q",0));
        return $found ? $this->json($found->_toArray()) : $this->json(null);
    }

    /**
     * @Route(
     *      "/car/follow/{kind}/data",
     *      name="car_follow_data",
     *      requirements={
     *          "kind": "fuel|maintain"
     *      },
     *      defaults={
     *          "kind": "fuel"
     *      })
     * @param Request $request
     * @param string $kind
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loadData(Request $request, string $kind)
    {
        $repository = $this->getDoctrine()->getRepository($kind == "fuel" ? Carburant::class : Entretien::class);
        $order = $request->get("order", [array("column" => 0, "dir" => "desc")]);
        $columns = $request->get("columns", [array("data" => "date")]);
        $rows = $repository->findBy(
            [],
            [$columns[$order[0]["column"]]["data"] => $order[0]["dir"]],
            $request->get("length", 25),
            $request->get("start")
        );
        return $this->json(array(
            "data" => array_map(function ($elem) {
                return $elem->_toArray();
            }, $rows)));
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
     * @Route("/car/data/maintain/new", name="car_data_maintain_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addEntretien(Request $request)
    {
        $maintain = new Entretien();
        $form = $this->createForm(EntretienTaskType::class, $maintain);
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
