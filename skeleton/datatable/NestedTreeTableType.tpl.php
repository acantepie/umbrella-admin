<?= "<?php\n"; ?>

namespace <?= $table->getNamespace(); ?>;

use <?= $entity->getClassName(); ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\LinkListColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\UmbrellaLink\UmbrellaLinkList;
use Umbrella\CoreBundle\Component\DataTable\Action\AddActionType;

class <?= $table->getShortClassName(); ?> extends DataTableType
{

    public function buildToolbar(ToolbarBuilder $builder, array $options = array())
    {
<?php if ('modal' === $view_type) {  ?>
        $builder->addAction('add', AddActionType::class, array(
            'route' => '<?= $routename_prefix; ?>_edit',
            'xhr' => true
        ));
<?php } else { ?>
        $builder->addAction('add', AddActionType::class, array(
            'route' => '<?= $routename_prefix; ?>_edit'
        ));
<?php } ?>
    }

    public function buildTable(DataTableBuilder $builder, array $options = array())
    {
        $builder->add('id', PropertyColumnType::class);
        $builder->add('actions', LinkListColumnType::class, array(
            'link_builder' => function (UmbrellaLinkList $linkList, <?= $entity->getShortClassName(); ?> $entity) {
<?php if ('modal' === $view_type) {  ?>
                $linkList->addXhrEdit('<?= $routename_prefix; ?>_edit', ['id' => $entity->id]);
<?php } else { ?>
                $linkList->addEdit('<?= $routename_prefix; ?>_edit', ['id' => $entity->id]);
<?php } ?>
                $linkList->addXhrDelete('<?= $routename_prefix; ?>_delete', ['id' => $entity->id]);
            }
        ));

        $builder->useNestedEntityAdapter(<?= $entity->getShortClassName(); ?>::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tree' => true
        ));
    }

}