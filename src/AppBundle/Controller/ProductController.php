<?php

namespace AppBundle\Controller;

use AppBundle\EventListener\ProductCreateListener;
use AppBundle\Events\ProductCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function create()
    {

        $factory = $this->container->get('sylius.factory.product');
        $product = $factory->createNew();

        $product->setCode('nowyy');
        $product->setName('nowyy');
        $product->setSlug('nowyy');

        $em = $this->get('doctrine.orm.entity_manager');
        $em->beginTransaction();


        $this->get('sylius.repository.product')->add($product);

        $em->persist($product);

        $em->flush($product);

        $productId = $product->getId();


        $listener = new ProductCreateListener();

        $dispatcher = new EventDispatcher();

        $dispatcher->addListener('app.product.create',array($listener, 'onCreateAction'));

        $event = new ProductCreatedEvent($product);
        $dispatcher->dispatch('app.product.create', $event);




        $em->rollback();


        return new Response('hello');
    }
}
