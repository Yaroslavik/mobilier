<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 07.10.15
 * Time: 11:39
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('slug', null, ['required' => false, 'attr' => ['readonly' => true]])
            ->add('content', null, ['attr' => ['class' => 'wysiwyg']])
            ->add('visible', null, ['required' => false])
            ->add('metaDescription', null, ['required' => false])
            ->add('metaKeywords', null, ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('visible');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('slug')
            ->add('visible', null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete')
            ->remove('create');
    }
}