<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\HttpFoundation;

use Symfony\Component\HttpFoundation\HeaderBag;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class HeaderBagMock extends HeaderBag
{
    const USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6';
    const REFERRER = 'http://www.google.com';

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $first = true)
    {
        switch ($key) {
            case 'referrer':
                return self::REFERRER;
                break;

            case 'User-Agent':
                return self::USER_AGENT;
                break;

            default:
                return null;
        }

    }
}
