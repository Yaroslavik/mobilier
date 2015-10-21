<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Application;
use AppBundle\Entity\ApplicationStatus;
use AppBundle\Entity\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\Validator\Constraints;

class DefaultController extends Controller
{
    protected $firstCommentsCount = 3;

    /**
     * Главная страница
     *
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

        return [
            'portfolioItems' => $portfolioItems,
            'firstComments' => $firstComments,
            'lastComments' => $lastComments,
        ];
    }

    /**
     * Мебель|Галерея работ
     *
     * @Route("/gallery", name="gallery")
     * @Template()
     */
    public function galleryAction()
    {
        // Images
        $portfolioItems = $this->getDoctrine()->getRepository('AppBundle:PortfolioItem')->getGalleryItems();
        return [
            'portfolioItems' => $portfolioItems,
        ];
    }

    /**
     * Обслуживание|Виды выполняемых работ
     *
     * @Route("/services", name="services")
     * @Template()
     */
    public function servicesAction()
    {
        return [];
    }

    /**
     * Материалы
     *
     * @Route("/info", name="info")
     * @Template()
     */
    public function informationAction()
    {
        return [];
    }

    /**
     * Калькулятор
     *
     * @Route("/calc", name="calc")
     * @Template()
     */
    public function calcAction()
    {
        return [];
    }

    /**
     * Тестовые страницы
     *
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

        return [
            'page' => $page,
        ];
    }

    /**
     * Заказ звонка
     *
     * @Route("/callback", name="callback", methods={"POST"})
     */
    public function callbackAction(Request $request)
    {
        /** @var ConfigRepository $configuration */
        $configuration = $this->get('app.configuration');

        try {
            $name = $request->get('name');
            $phone = $request->get('phone');
            if (!$name || !$phone) throw new \Exception('Не указано имя и/или номер телефона.');

            // Сохранение заявки
            $statusOpen = $this->getDoctrine()
                ->getRepository('AppBundle:ApplicationStatus')
                ->findOneBy(['code' => ApplicationStatus::CODE_OPEN]);

            $application = new Application();
            $application->setName($name);
            $application->setPhone($phone);
            $application->setStatus($statusOpen);
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            // Проверка email менеджера
            $email = trim($configuration->get('EMAIL_MANAGER'));
            $notBlankConstraint = new Constraints\NotBlank();
            $emailConstraint = new Constraints\Email();

            $constraintViolations = $this->get('validator')->validate(
                $email,
                [$notBlankConstraint, $emailConstraint]
            );

            $errors = [];
            foreach ($constraintViolations as $violation) {
                $errors[] = $violation->getMessage();
            }

            if ($constraintViolations->count()) throw new \Exception(join(' ', $errors) . $email);

            // Рендер письма
            $messageBody = $this->get('templating')->render('AppBundle::message_callback.html.twig', [
                'name' => $name,
                'phone' => $phone,
                'created_at' => $application->getCreatedAt(),
            ]);

            // Отправка письма
            $mailer = $this->get('mailer');
            /** @var \Swift_Message $message */
            $message = $mailer->createMessage();
            $message->setFrom(['manager@mobilier.by' => 'Mobilier.by'])
                ->setTo($email)
                ->setSubject('Заявка на звонок')
                ->setBody($messageBody)
                ->setContentType('text/html');

            $this->get('mailer')->send($message);

            $data['status'] = 'success';
            $data['message'] = $configuration->get('CALLBACK_SUCCESS');
        } catch(\Exception $e) {
            $data['status'] = 'fail';
            $data['message'] = $this->get('kernel')->isDebug() ? $e->getMessage() : $configuration->get('CALLBACK_FAIL');
        }

        return new JsonResponse($data);
    }

    /**
     * 404
     *
     * @param FlattenException $exception
     * @param DebugLoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function exceptionAction(FlattenException $exception, DebugLoggerInterface $logger)
    {
        return $this->redirect($this->generateUrl('page', ['url' => '404']));
    }
}
