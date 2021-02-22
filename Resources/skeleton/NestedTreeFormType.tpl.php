<?php echo "<?php\n"; ?>

namespace <?php echo $form->getNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use <?php echo $entity->getClassName(); ?>;
use Umbrella\CoreBundle\Form\NestedTreeParentType;

class <?php echo $form->getShortClassName(); ?> extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', NestedTreeParentType::class, [
            'class' => <?php echo $entity->getShortClassName(); ?>::class,
            'current_node' => $builder->getData(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?php echo $entity->getShortClassName(); ?>::class,
        ));
    }
}
