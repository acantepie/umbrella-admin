<?php

namespace Umbrella\AdminBundle\Notification\Provider;

use Umbrella\AdminBundle\Entity\BaseNotification;

/**
 * Interface NotificationProviderInterface
 */
interface NotificationProviderInterface
{
    /**
     * @param object $user
     *
     * @return iterable|BaseNotification[]
     */
    public function findByUser($user): iterable;
}
