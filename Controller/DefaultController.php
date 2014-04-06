<?php

namespace Frne\Bundle\Neo4jUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrneNeo4jUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
