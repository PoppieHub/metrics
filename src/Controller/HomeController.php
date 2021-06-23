<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\Website;
use App\Form\WebsiteFormType;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class HomeController extends BaseController
{
    private const FLASH_INFO = 'info';

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender = parent::actionMetric();
        $forRender['title'] = 'Главная';
        return $this->render('index.html.twig', $forRender);
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contacts()
    {
        $forRender = parent::renderDefault();
        $forRender = parent::actionMetric();
        $forRender['title'] = 'Контакты';
        return $this->render('contacts.html.twig', $forRender);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        $forRender = parent::renderDefault();
        $forRender = parent::actionMetric();
        $forRender['title'] = 'О нас';
        return $this->render('about.html.twig', $forRender);
    }

    /**
     * @Route("/metrics", name="metrics")
     */
    public function metrics()
    {
        $website = $this->getDoctrine()-> getRepository(Website::class)->findBy( array(),
            array('name' => 'ASC'));

        $users = $this->getDoctrine()->getRepository(User::class)->findVisitors();
        $countUnique = 0;
        $count = 0;

        foreach ($users as &$user){
            $countUnique++;
            $count = $count + current($user);
        }

        $devices = $this->getDoctrine()->getRepository(User::class)->findDevices();
        $deviceType = [];
        $deviceCount = [];

        foreach ($devices as $device){
            $deviceType[] = $device['type'];
            $deviceCount[] = $device['count'];
        }

        $os = $this->getDoctrine()->getRepository(User::class)->findOs();
        $osType = [];
        $osCount = [];

        foreach ($os as $device){
            $osType[] = $device['os'];
            $osCount[] = $device['count'];
        }

        $browsers = $this->getDoctrine()->getRepository(User::class)->findBrowsers();
        $browsersType = [];
        $browsersCount = [];

        foreach ($browsers as $browser){
            $browsersType[] = $browser['browser'];
            $browsersCount[] = $browser['count'];
        }

        $nowDate = new \DateTime();
        $year = $nowDate->format('Y');
        $month = $nowDate->format('m');
        $nowDate->setDate($year,$month,'-30');
        $oneDate = $nowDate->format('Y-m-d 00:00:00');
        $queryForDate = $this->getDoctrine()-> getRepository(User::class)->findCountForTheDate($oneDate);

        foreach ($queryForDate as $visit){
            $dates[] = $visit['date'];
            $datesCounts[] = $visit['count'];
        }


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Метрика';
        $forRender['websites'] = $website;
        $forRender['countUnique'] = $countUnique;
        $forRender['deviceType'] = json_encode($deviceType);
        $forRender['deviceCount'] = json_encode($deviceCount);
        $forRender['osType'] = json_encode($osType);
        $forRender['osCount'] = json_encode($osCount);
        $forRender['browsersType'] = json_encode($browsersType);
        $forRender['browsersCount'] = json_encode($browsersCount);
        $forRender['dates'] = json_encode($dates);
        $forRender['datesCounts'] = json_encode($datesCounts);
        $forRender['count'] = $count;
        return $this->render('metrics/metrics.html.twig', $forRender);
    }

    /**
     * @Route("/website/delete/{id}", name="website_delete")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function websiteDelete(Website $website, EntityManagerInterface $em) :Response
    {
        $users = $website->getWebsite()->getValues();
        $dates = $website->getDates()->getValues();
        foreach ($dates as &$date){
            $em->remove($date);
            $em->flush();
        }
        foreach ($users as &$user){
            $em->remove($user);
            $em->flush();
        }
        $em->getRepository(Website::class)->deleteView($website->getId());
        $this->addFlash(self::FLASH_INFO, 'Страница больше не индексируется!');
        return $this->redirectToRoute('metrics');
    }

    /**
     * @Route("/website/create", name="website_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createWebsite(Request $request, EntityManagerInterface $em) :Response
    {
        $website = new Website();
        $form = $this->createForm(WebsiteFormType::class, $website);
        $form->handleRequest($request);

        if(($form->isSubmitted()) and ($form->isValid()))
        {
            $name = $form->get('name')->getData();
            $url = $form->get('url_website')->getData();
            $website->setName($name);
            $website->setUrlWebsite($url);
            $website->setPageView('0');
            $em->persist($website);
            $em->flush();

            $this->addFlash(self::FLASH_INFO, 'Страница добавлена!');
            return $this->redirectToRoute('metrics');
        }
        $forRender = parent::renderDefault();
        $forRender['form'] = $form->createView();
        return $this->render('metrics/form.html.twig',$forRender);

    }

}