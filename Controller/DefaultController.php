<?php

namespace MESD\UniversalAuthenticatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $rep = $this->getDoctrine()->getManager()->getRepository('MESDUniversalAuthenticatorBundle:AuthService');
        $services = $rep->findAll();
        array_map(function($service) {
            var_dump(get_class($service));
        }, $services);
        $rep   = $this->getDoctrine()->getManager()->getRepository('MESDUniversalAuthenticatorBundle:AuthLDAP');
        $ldaps = $rep->findAll();
        $rep   = $this->getDoctrine()->getManager()->getRepository('MESDUniversalAuthenticatorBundle:AuthPDO');
        $pdos  = $rep->findAll();

        return $this->render(
                'MESDUniversalAuthenticatorBundle:Default:index.html.twig',
                array(
                    'entitiess' => array($services, $ldaps, $pdos)
                )
        );
    }
}
