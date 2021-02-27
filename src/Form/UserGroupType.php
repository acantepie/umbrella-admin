<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Form\Choice2Type;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class UserGroupType.
 */
class UserGroupType extends AbstractType
{
    private ParameterBagInterface $parameters;

    /**
     * UserGroupType constructor.
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $this->parameters->get('umbrella_admin.user.group.form_roles');

        $builder->add('title', TextType::class);
        $builder->add('roles', Choice2Type::class, [
            'choices' => ArrayUtils::values_as_keys($roles),
            'multiple' => true,
            'choice_prefix' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->parameters->get('umbrella_admin.user.group.class'),
        ]);
    }
}
