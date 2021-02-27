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
            new TwigFunction('admin_home_route', [$this, 'homeRoute']),

            new TwigFunction('admin_theme_name', [$this, 'themeName']),
            new TwigFunction('admin_theme_logo', [$this, 'themeLogo']),

            new TwigFunction('admin_script_entry', [$this, 'scriptEntry']),
            new TwigFunction('admin_stylesheet_entry', [$this, 'stylesheetEntry']),

            new TwigFunction('admin_profile_enabled', [$this, 'profileEnable']),
            new TwigFunction('admin_profile_route', [$this, 'routeProfile']),

            new TwigFunction('admin_notification_enabled', [$this, 'notificationEnabled']),
            new TwigFunction('admin_notification_poll_intervall', [$this, 'notificationPollInterval']),
        ];
    }

    public function homeRoute()
    {
        return $this->parameters->get('umbrella_admin.home_route');
    }

    // Theme

    public function themeName()
    {
        return $this->parameters->get('umbrella_admin.theme.name');
    }

    public function themeLogo()
    {
        return $this->parameters->get('umbrella_admin.theme.logo');
    }

    // Assets

    public function scriptEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.script_entry');
    }

    public function stylesheetEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.stylesheet_entry');
    }

    // User

    public function profileEnable()
    {
        return $this->parameters->get('umbrella_admin.user.profile.enabled');
    }

    public function routeProfile()
    {
        return $this->parameters->get('umbrella_admin.user.profile.route');
    }

    // Notification

    public function notificationEnabled()
    {
        return $this->parameters->get('umbrella_admin.notification.enabled');
    }

    public function notificationPollInterval()
    {
        return $this->parameters->get('umbrella_admin.notification.poll_interval');
    }
}
