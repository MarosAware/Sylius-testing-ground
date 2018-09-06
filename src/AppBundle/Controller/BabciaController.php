<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class BabciaController extends Controller
{
    public function index()
    {
        $factory = $this->container->get('sylius.factory.product');

        $product = $factory->createNew();

        $product->setCode('babcia_product');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response("<pre>" . var_dump($product));
    }


    public function index2()
    {
        return new Response("<pre>" . var_dump(Intl::getRegionBundle()->getCountryNames()));
    }
}
