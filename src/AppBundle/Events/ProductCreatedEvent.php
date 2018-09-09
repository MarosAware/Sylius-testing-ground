<?php
/**
 * Created by PhpStorm.
 * User: marosaware
 * Date: 09.09.18
 * Time: 13:28
 */

namespace AppBundle\Events;


use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\Event;

class ProductCreatedEvent extends Event
{
    protected $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }
}