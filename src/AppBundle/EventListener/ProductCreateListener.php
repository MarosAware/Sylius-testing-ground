<?php
/**
 * Created by PhpStorm.
 * User: marosaware
 * Date: 09.09.18
 * Time: 13:11
 */

namespace AppBundle\EventListener;


use AppBundle\Events\ProductCreatedEvent;

class ProductCreateListener
{
    public function onCreateAction(ProductCreatedEvent $event)
    {
        dump($event->getProduct());
    }
}