<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Product\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'product',
                'reference',
                array(
                    'class' => 'Lighthouse\\CoreBundle\\Document\\Product\\Product',
                    'invalid_message' => 'lighthouse.validation.errors.product.does_not_exist'
                )
            )
            ->add(
                'store',
                'reference',
                array(
                    'class' => 'Lighthouse\\CoreBundle\\Document\\Store\\Store',
                    'invalid_message' => 'lighthouse.validation.errors.store.does_not_exist'
                )
            )
            ->add('retailPrice', 'money')
            ->add('retailMarkup', 'markup')
            ->add('retailPricePreference', 'choice', array('choices' => Product::$retailPricePreferences))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Lighthouse\\CoreBundle\\Document\\Product\\Store\\StoreProduct',
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
