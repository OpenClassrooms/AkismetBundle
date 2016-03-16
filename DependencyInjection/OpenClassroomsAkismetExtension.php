<?php

namespace OpenClassrooms\Bundle\AkismetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class OpenClassroomsAkismetExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $config);

        $container->setParameter('openclassrooms.akismet.key', $config['key']);
        $container->setParameter('openclassrooms.akismet.blog', $config['blog']);
    }

    /**
     * @inheritdoc
     */
    public function getAlias()
    {
        return 'openclassrooms_akismet';
    }
}
