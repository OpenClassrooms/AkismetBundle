<?php

namespace OpenClassrooms\Bundle\AkismetBundle\Tests\Doubles\HttpFoundation;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class RequestStackMock extends RequestStack
{
    /**
     * {@inheritdoc}
     */
    public function getCurrentRequest()
    {
        return new RequestMock();
    }
}
