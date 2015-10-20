<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 07.10.15
 * Time: 10:55
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ApplicationAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('createdAt', null, [
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['readonly' => true]])
            ->add('updatedAt', null, [
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['readonly' => true]])
            ->add('name')
            ->add('phone')
            ->add('comment')
            ->add('status');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('phone')
            ->add('status');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('createdAt')
            ->add('name')
            ->add('phone')
            ->add('status');
    }
}