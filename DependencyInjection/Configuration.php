<?php

namespace OpenClassrooms\Bundle\AkismetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Arnaud Lefèvre <arnaud.lefevre@openclassrooms.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('openclassrooms_akismet');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->children()
            ->scalarNode('key')->isRequired()->end()
            ->scalarNode('blog')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
