<?php

namespace OpenClassrooms\Bundle\AkismetBundle;

use OpenClassrooms\Bundle\AkismetBundle\DependencyInjection\OpenClassroomsAkismetExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class OpenClassroomsAkismetBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new OpenClassroomsAkismetExtension();
    }
}
