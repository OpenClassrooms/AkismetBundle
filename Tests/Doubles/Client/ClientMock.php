<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\Client;

use OpenClassrooms\Akismet\Client\Client;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class ClientMock implements Client
{

    /**
     * @var bool
     */
    public static $postReturn;

    /**
     * @var string
     */
    public static $resource;

    /**
     * @var array
     */
    public static $params;

    public function __construct()
    {
        self::$resource = null;
        self::$params = null;
    }

    /**
     * {@inheritdoc}
     */
    public function post($resource, array $params)
    {
        self::$resource = $resource;
        self::$params = $params;

        return self::$postReturn;
    }
}
