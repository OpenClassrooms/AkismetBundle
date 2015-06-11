<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class RequestStackMock extends RequestStack
{
    const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6';
    const REFERRER = 'http://www.google.com';

    /**
     * {@inheritdoc}
     */
    public function getCurrentRequest()
    {
        $request = Request::create('http://localhost');
        $request->headers->set('User-Agent', self::USER_AGENT);
        $request->headers->set('referrer', self::REFERRER);

        return $request;
    }
}
