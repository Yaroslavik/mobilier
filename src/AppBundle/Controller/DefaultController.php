<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\CommentRepository;

class DefaultController extends Controller
{
    protected $firstCommentsCount = 3;

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->getActualComments();
        $firstComments = [];
        $lastComments = [];

        $count = count($comments);
        for ($i = 0; $i < $count; $i++) {
            if ($i < $this->firstCommentsCount) {
                $firstComments[] = $comments[$i];
            } else {
                $lastComments[] = $comments[$i];
            }
        }

        return array(
            'firstComments' => $firstComments,
            'lastComments' => $lastComments,
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
