<?php
/**
 * Created by PhpStorm.
 * User: n.chauvire
 * Date: 04/10/2017
 * Time: 09:16
 */

namespace Src\Controllers;


use MongoClient;
use MongoDB;
use MongoDB\Client;
use Slim\Container;

class Controller
{

    /**
     * @var Container
     */
    private $container;


    /**
     * controleur constructor.
     *
     * @param $container
     */
    public function __construct ($container)
    {
        $this->container = $container;
    }

    /**
     * @return Client
     */
    public function getMongo ()
    {
        return $this->container->get('mongodb');
    }

}