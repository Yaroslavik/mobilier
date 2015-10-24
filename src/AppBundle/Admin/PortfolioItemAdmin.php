<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 07.10.15
 * Time: 11:41
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PortfolioItemAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $image = $this->getSubject();

        $fileFieldOptions = array('required' => false);
        if ($image && $image->getWebPath()) {
            $fileFieldOptions['help'] = '<img src="' . $image->getWebPath() . '" class="admin-preview">';
        }

        $formMapper
            ->add('category')
            ->add('title')
            ->add('file', 'file', $fileFieldOptions)
            ->add('description', null, ['required' => false])
            //->add('order')
            ->add('visible', null, ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category')
            ->add('title')
            ->add('visible');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('file', null, ['template' => 'AppBundle:Admin:list_field_image.html.twig'])
            ->addIdentifier('title')
            ->add('category')
            ->add('visible');
    }
}