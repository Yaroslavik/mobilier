<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class DefaultController extends Controller
{
    protected $firstCommentsCount = 3;

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        // Images
        $portfolioItems = $this->getDoctrine()->getRepository('AppBundle:PortfolioItem')->getHomepageItems();

        // Comments
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
            'portfolioItems' => $portfolioItems,
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
     * @Route("/info", name="info")
     * @Template()
     */
    public function informationAction()
    {
        return array();
    }

    /**
     * @Route("/gallery", name="gallery")
     * @Template()
     */
    public function galleryAction()
    {
        // Images
        $portfolioItems = $this->getDoctrine()->getRepository('AppBundle:PortfolioItem')->getVisibleItems();
        return array(
            'portfolioItems' => $portfolioItems,
        );
    }

    /**
     * @Route("/page/{url}", name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Page');
        $page = $repository->findOneBy(['slug' => $url]);

        if (!$page) {
            throw new NotFoundHttpException("Страница не найдена.");
        }

        return array(
            'page' => $page,
        );
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

    /**
     * @Route("/callback", name="callback", methods={"POST"})
     */
    public function callbackAction(Request $request)
    {
        try {
            $configuration = $this->get('app.configuration');

            $name = $request->get('name');
            $phone = $request->get('phone');
            if (!$name || !$phone) throw new \Exception('Не указано имя и/или номер телефона.');

            // Сохранение заявки
            $application = new Application();
            $application->setName($name);
            $application->setPhone($phone);
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            // Рендер письма
            $messageBody = $this->get('templating')->render('AppBundle::message_callback.html.twig', [
                'name' => $name,
                'phone' => $phone,
                'created_at' => $application->getCreatedAt(),
            ]);

            // Отправка письма
            /** @mytodo Протестировать отправку на продакшене */
            //$mailer = $this->get('mailer');
            /** @var \Swift_Message $message */
            //$message = $mailer->createMessage();
            //$message->setTo($configuration->get('EMAIL_MANAGER'));
            //$message->setBody($messageBody);
            //$mailer->send($message);

            $data['message'] = $configuration->get('CALLBACK_SUCCESS');
        } catch(\Exception $e) {
            $data['message'] = $this->get('kernel')->isDebug() ? $e->getMessage() : $configuration->get('CALLBACK_FAIL');
        }

        return new JsonResponse($data);
    }

    public function exceptionAction(FlattenException $exception, DebugLoggerInterface $logger)
    {
        return $this->redirect($this->generateUrl('page', ['url' => '404']));
    }
}
