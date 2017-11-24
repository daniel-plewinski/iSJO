<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectController extends Controller
{
    /**
     * @Route("/")
     */
    public function roleRedirectAction()
    {
        $this->denyAccessUnlessGranted(
            ['ROLE_TEACHER', 'ROLE_SCHOOL', 'ROLE_ADMIN'],
            null,
            'Musisz się zalogować'
        );

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SCHOOL')) {

            return $this->redirectToRoute("index_school");

        } else {
            return new Response("Nie Jesteś szkołą");
        }
    }
}
