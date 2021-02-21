<?php

namespace Umbrella\AdminBundle\Extension;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AdminTwigExtension.
 */
class AdminTwigExtension extends AbstractExtension
{
    private ParameterBagInterface $parameters;

    /**
     * AdminTwigExtension constructor.
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('admin_theme_name', [$this, 'themeName']),
            new TwigFunction('admin_theme_logo', [$this, 'themeLogo']),
            new TwigFunction('admin_script_entry', [$this, 'scriptEntry']),
            new TwigFunction('admin_stylesheet_entry', [$this, 'stylesheetEntry']),
            new TwigFunction('admin_route_profile', [$this, 'routeProfile']),
            new TwigFunction('admin_route_logout', [$this, 'routeLogout']),
            new TwigFunction('admin_notification_enabled', [$this, 'notificationEnabled']),
            new TwigFunction('admin_notification_poll_intervall', [$this, 'notificationPollInterval']),
        ];
    }

    public function themeName()
    {
        return $this->parameters->get('umbrella_admin.theme.name');
    }

    public function themeLogo()
    {
        return $this->parameters->get('umbrella_admin.theme.logo');
    }

    public function scriptEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.script_entry');
    }

    public function stylesheetEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.stylesheet_entry');
    }

    public function routeProfile()
    {
        return $this->parameters->get('umbrella_admin.route.profile');
    }

    public function routeLogout()
    {
        return $this->parameters->get('umbrella_admin.route.logout');
    }

    public function notificationEnabled()
    {
        return $this->parameters->get('umbrella_admin.notification.enabled');
    }

    public function notificationPollInterval()
    {
        return $this->parameters->has('umbrella_admin.notification.poll_interval')
            ? $this->parameters->get('umbrella_admin.notification.poll_interval') : 0;
    }
}
