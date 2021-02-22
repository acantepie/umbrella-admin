<?php echo "<?php\n"; ?>

namespace <?php echo $table->getNamespace(); ?>;

use <?php echo $entity->getClassName(); ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Column\Type\PropertyColumnType;
use Umbrella\CoreBundle\Component\Column\Type\LinkListColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;
use Umbrella\CoreBundle\Component\UmbrellaLink\UmbrellaLinkList;
use Umbrella\CoreBundle\Component\Action\Type\AddActionType;
use Umbrella\CoreBundle\Form\SearchType;

class <?php echo $table->getShortClassName(); ?> extends DataTableType
{

    public function buildToolbar(ToolbarBuilder $builder, array $options = array())
    {
        $builder->addFilter('search', SearchType::class);
<?php if ('modal' === $view_type) { ?>
        $builder->addAction('add', AddActionType::class, array(
            'route' => '<?php echo $routename_prefix; ?>_edit',
            'xhr' => true
        ));
<?php } else { ?>
        $builder->addAction('add', AddActionType::class, array(
            'route' => '<?php echo $routename_prefix; ?>_edit'
        ));
<?php } ?>
    }

    public function buildTable(DataTableBuilder $builder, array $options = array())
    {
        $builder->add('id', PropertyColumnType::class);
        $builder->add('actions', LinkListColumnType::class, array(
            'link_builder' => function (UmbrellaLinkList $linkList, <?php echo $entity->getShortClassName(); ?> $entity) {
<?php if ('modal' === $view_type) { ?>
                $linkList->addXhrEdit('<?php echo $routename_prefix; ?>_edit', ['id' => $entity->id]);
<?php } else { ?>
                $linkList->addEdit('<?php echo $routename_prefix; ?>_edit', ['id' => $entity->id]);
<?php } ?>
                $linkList->addXhrDelete('<?php echo $routename_prefix; ?>_delete', ['id' => $entity->id]);
            }
        ));

        $builder->useEntityAdapter(<?php echo $entity->getShortClassName(); ?>::class);
    }

}