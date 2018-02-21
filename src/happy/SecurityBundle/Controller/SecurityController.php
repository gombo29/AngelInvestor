<?php

namespace happy\SecurityBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends BaseController
{
    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {
        return $this->render('happySecurityBundle:Security:login.html.twig',
            $data + array('helpmenu' => $this->menuDefault())
        );
    }


    private function menuDefault()
    {
        $em = $this->container->get('doctrine')->getManager();
        $qb = $em->getRepository('happyCmsBundle:ContentPosition')->createQueryBuilder('n');
        $types = $qb
            ->where('n.id > 4')
            ->getQuery()
            ->getArrayResult();

        return $types;
    }
}



