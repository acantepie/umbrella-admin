<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Umbrella\CoreBundle\Utils\ArrayUtils;
use Umbrella\AdminBundle\Menu\SidebarMenu;
use Symfony\Component\DependencyInjection\Loader;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\AdminBundle\FileWriter\Handler\AbstractFileWriterHandler;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaAdminExtension extends Extension implements PrependExtensionInterface
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

        $container->registerForAutoconfiguration(AbstractFileWriterHandler::class)->addTag('umbrella.filewriter.handler');

        $parameters = ArrayUtils::remap_nested_array($config, 'umbrella_admin', ['umbrella_admin.user.group_crud.form_roles']);
        $parameters['umbrella_admin.route.profile'] = 'umbrella_admin_profile_index';
        $parameters['umbrella_admin.route.logout'] = 'umbrella_admin_logout';

        foreach ($parameters as $pKey => $pValue) {
            if (!$container->hasParameter($pKey)) {
                $container->setParameter($pKey, $pValue);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        // add some config for doctrine bundle
        // orm:
        //      resolve_target_entities:
        //             Umbrella\AdminBundle\Model\AdminUserInterface : <value of umbrella_admin.user.user_crud.class config>
        //
        $configs = $container->getExtensionConfig('umbrella_admin');
        $config = $this->processConfiguration(new Configuration(), $configs);

        $doctrineConfig = [];
        $doctrineConfig['orm']['resolve_target_entities'][AdminUserInterface::class] = $config['user']['user_crud']['class'];
        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
