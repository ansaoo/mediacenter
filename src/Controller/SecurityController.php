<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 06/05/18
 * Time: 21:10
 */

namespace App\Controller;

use App\Entity\LoginTask;
use App\Form\LoginTaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginTask = new LoginTask();
        $loginTask->setClientLogin($lastUsername);
        $form = $this->createForm(LoginTaskType::class, $loginTask);
        $form->handleRequest($request);

        return $this->render('security/login.html.twig', array(
//            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/logout.html.twig', array(
            'last_username' => $lastUsername
        ));
    }
}