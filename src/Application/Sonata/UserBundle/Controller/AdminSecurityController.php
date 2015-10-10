<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 10.10.15
 * Time: 13:36
 */

namespace Application\Sonata\UserBundle\Controller;

use Sonata\UserBundle\Controller;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminSecurityController extends Controller\AdminSecurityController
{
    public function loginAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_admin_dashboard');

            return new RedirectResponse($url);
        }

        return parent::loginAction();
    }
}