<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class RequestMock extends Request
{
    const CLIENT_IP = '127.0.0.1';

    public function __construct()
    {
        $this->headers = new HeaderBagMock();
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp()
    {
        return self::CLIENT_IP;
    }
}
