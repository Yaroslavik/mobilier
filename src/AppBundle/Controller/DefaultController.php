<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'metaDescription' => 'Описание',    /** @mytodo Заменить на реальные значения */
            'metaKeywords' => 'Ключевые слова',
        );
    }

    /**
     * @Route("/services", name="services")
     * @Template()
     */
    public function servicesAction()
    {
        return array();
    }

    /**
     * @Route("/gallery", name="gallery")
     * @Template()
     */
    public function galleryAction()
    {
        return array();
    }

    /**
     * @Route("/page/{url}", name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        return array();
    }

    /**
     * @Route("/info", name="info")
     * @Template()
     */
    public function infoAction()
    {
        return array();
    }

    /**
     * @Template("@App/header.html.twig")
     */
    public function headerAction()
    {
        return array();
    }

    /**
     * @Template("@App/navigation.html.twig")
     */
    public function navigationAction()
    {
        return array();
    }

    /**
     * @Template("@App/footer.html.twig")
     */
    public function footerAction()
    {
        return array();
    }
}
