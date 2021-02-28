<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Umbrella\CoreBundle\Component\DataTable\Action\AddActionType;
use Umbrella\CoreBundle\Component\DataTable\Column\LinkListColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\ManyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\UmbrellaLink\UmbrellaLinkList;
use Umbrella\CoreBundle\Form\SearchType;

/**
 * Class UserGroupTableType.
 */
class UserGroupTableType extends DataTableType
{
    private ParameterBagInterface $parameters;

    /**
     * UserGroupTableType constructor.
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options = [])
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addAction('add', AddActionType::class, [
            'route' => 'umbrella_admin_usergroup_edit',
            'label' => 'add_group',
            'xhr' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
        $builder->add('title', PropertyColumnType::class);
        $builder->add('roles', ManyColumnType::class);

        $builder->add('actions', LinkListColumnType::class, [
            'link_builder' => function (UmbrellaLinkList $linkList, $entity) {
                $linkList->addXhrEdit('umbrella_admin_usergroup_edit', ['id' => $entity->id]);
                $linkList->addXhrDelete('umbrella_admin_usergroup_delete', ['id' => $entity->id]);
            },
        ]);

        $builder->useEntityAdapter([
            'class' => $this->parameters->get('umbrella_admin.user.group.class'),
            'query' => function (QueryBuilder $qb, $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('lower(e.title) LIKE :search');
                    $qb->setParameter('search', '%' . strtolower($formData['search']) . '%');
                }
            }
        ]);
    }
}
