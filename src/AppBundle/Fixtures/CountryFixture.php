<?php
/**
 * Created by PhpStorm.
 * User: marosaware
 * Date: 06.09.18
 * Time: 21:32
 */

namespace AppBundle\Fixtures;


use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Addressing\Model\Country;
use Symfony\Component\Intl\Intl;

class CountryFixture extends AbstractFixture implements FixtureInterface
{

    private $countryManager;

    public function __construct(ObjectManager $countryManager)
    {
        $this->countryManager = $countryManager;
    }

    /**
     * @param array $options
     */
    public function load(array $options): void
    {
        $countriesCode = array_keys(Intl::getRegionBundle()->getCountryNames());

        foreach ($countriesCode as $countryCode) {
            $country = new Country();

            if ($countryCode === "US") {
                continue;
            }
            $country->setCode($countryCode);
            $this->countryManager->persist($country);
        }

        $this->countryManager->flush();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'country';
    }
}