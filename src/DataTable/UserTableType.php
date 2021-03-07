<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Umbrella\AdminBundle\DataTable\Column\UserNameColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\LinkListColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\ManyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\ToggleColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\UmbrellaLink\UmbrellaLinkList;
use Umbrella\CoreBundle\Component\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Form\SearchType;

/**
 * Class UserTableType.
 */
class UserTableType extends DataTableType
{
    private ParameterBagInterface $parameters;

    /**
     * UserTableType constructor.
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
        $builder->addWidget('add_user', AddLinkType::class, [
            'route' => 'umbrella_admin_user_edit',
            'xhr' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
        $builder->add('name', UserNameColumnType::class);
        $builder->add('createdAt', DateColumnType::class);
        $builder->add('groups', ManyColumnType::class, [
            'one_path' => 'title',
        ]);
        $builder->add('active', ToggleColumnType::class, [
            'route' => 'umbrella_admin_user_toggleactive',
            'route_params' => function ($entity) {
                return ['id' => $entity->id];
            },
        ]);
        $builder->add('actions', LinkListColumnType::class, [
            'link_builder' => function (UmbrellaLinkList $linkList, $entity) {
                $linkList->addXhrEdit('umbrella_admin_user_edit', ['id' => $entity->id]);
                $linkList->addXhrDelete('umbrella_admin_user_delete', ['id' => $entity->id]);
            },
        ]);

        $builder->useEntityAdapter([
            'class' => $this->parameters->get('umbrella_admin.user.user.class'),
            'query' => function (QueryBuilder $qb, $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('lower(e.search) LIKE :search');
                    $qb->setParameter('search', '%' . strtolower($formData['search']) . '%');
                }
            }
        ]);
    }
}
