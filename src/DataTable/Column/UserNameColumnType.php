<?php

namespace Umbrella\AdminBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class UserNameColumnType
 */
class UserNameColumnType extends PropertyColumnType
{
    private UmbrellaFileHelper $fileHelper;

    /**
     * UserTableType constructor.
     */
    public function __construct(UmbrellaFileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function render($user, array $options)
    {
        if (!is_a($user, AdminUserInterface::class)) {
            throw new \RuntimeException(sprintf('Can\'t render user::name, expected "%s" class.', AdminUserInterface::class));
        }

        $html = '<div class="d-flex">';
        if ($user->getAvatar()) {
            $html .= sprintf('<img src="%s" class="avatar-sm rounded-circle mr-2">', $this->fileHelper->getImageUrl($user->getAvatar(), 'user_avatar'));
        } else {
            $html .= '<div class="avatar-sm avatar-icon rounded-circle mr-2"><i class="uil-user font-20"></i></div>';
        }
        $html .= '<div>';
        $html .= sprintf('<div>%s</div>', HtmlUtils::escape($user->getFullName()));
        $html .= sprintf('<div class="text-muted">%s</div>', HtmlUtils::escape($user->getUsername()));
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('order', 'ASC')
            ->setDefault('order_by', ['firstname', 'lastname'])
            ->setDefault('is_safe_html', true);
    }
}
