<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Class AccountDisabledException
 */
class AccountDisabledException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'account_disabled';
    }
}
