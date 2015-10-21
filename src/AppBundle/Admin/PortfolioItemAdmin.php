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
        // get the current Image instance
        $image = $this->getSubject();

        // use $fileFieldOptions so we can add other options to the field
        $fileFieldOptions = array('required' => false);
        if ($image && ($webPath = $image->getWebPath())) {
            // get the container so the full path to the image can be set
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request')->getBasePath() . '/' . $webPath;

            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" style="max-width: 100%" />';
        }

        $formMapper
            // ... other fields ...
            ->add('file', 'file', $fileFieldOptions)
        ;

        $formMapper
            ->add('title')
            ->add('file', 'file', $fileFieldOptions)
            ->add('description', null, ['required' => false])
            ->add('order')
            ->add('inGallery', null, ['required' => false])
            ->add('onHomepage', null, ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('inGallery')
            ->add('onHomepage');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('file', null, ['template' => 'AppBundle:Admin:list_field_image.html.twig'])
            ->addIdentifier('title')
            ->add('inGallery')
            ->add('onHomepage');
    }
}