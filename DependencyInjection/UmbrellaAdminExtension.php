<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Umbrella\AdminBundle\Menu\SidebarMenu;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $def = $container->getDefinition(SidebarMenu::class);
        $def->replaceArgument(0, $config['menu']['file']);

        $parameters = ArrayUtils::remap_nested_array($config, 'umbrella_admin');
        foreach ($parameters as $pKey => $pValue) {
            if (!$container->hasParameter($pKey)) {
                $container->setParameter($pKey, $pValue);
            }
        }
    }
}
