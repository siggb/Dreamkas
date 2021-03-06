<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class StockInType extends DocumentType
{
    /**
     * @var bool
     */
    protected $store;

    /**
     * @param bool $store
     */
    public function __construct($store = false)
    {
        $this->store = (bool) $store;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime')
            ->add(
                'products',
                'collection',
                array(
                    'type' => new StockInProductType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
        ;

        if ($this->store) {
            $builder->add(
                'store',
                'reference',
                array(
                    'class' => Store::getClassName(),
                    'return_null_object_on_not_found' => true,
                )
            );
        }
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return StockIn::getClassName();
    }
}
