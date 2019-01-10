<?php

namespace App\Factory;
use GuzzleHttp\Client;
use SilverStripe\Core\Injector\Factory;

class ClientFactory implements Factory
{
        /**
     * Creates a new service instance.
     *
     * @param string $service The class name of the service.
     * @param array $params The constructor parameters.
     * @return object The created service instances.
     */
    public function create($service, array $params = array())
    {
        $client = new Client(['timeout'=>5.0, 'connect_timeout'=>5.0]);

        return $client;
    }
}
