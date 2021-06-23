<?php


namespace App\Controller;


use App\Entity\Date;
use App\Entity\User;
use App\Entity\Website;
use DeviceDetector\DeviceDetector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BaseController extends AbstractController
{
    public function renderDefault()
    {
        return [
            'title' => 'Метрика'
        ];

    }

    public function actionMetric()
    {
        $em = $this->getDoctrine()->getManager();
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $dd = new DeviceDetector($userAgent);
        $dd->parse();
        if ($dd->isMobile()){
            $DeviceName = 'Smartphone';
        }
        elseif($dd->isTablet()){
            $DeviceName = 'Tablet';
        }
        elseif($dd->isDesktop()){
            $DeviceName = 'PC';
        }
        else{
            $DeviceName = 'Other';
        }
        $url=(isset($_SERVER['HTTPS'])) ? "https":"http";
        $url.="://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $os = $dd->getOs('name');
        $browser = $dd->getClient('name');
        $browserVersion = $dd->getClient('version');
        $ip = $_SERVER['REMOTE_ADDR'];
        $website = $em->getRepository(Website::class)->findOneBy(['url_website'=>$url]);
        $today = new \DateTime();
        if ($website){
            $count = $website->getPageView();

            $user = new User();
            $user->setBrowser($browser);
            $user->setIp($ip);
            $user->setOs($os);
            $user->setType($DeviceName);
            $user->setVersion($browserVersion);
            $user->setWebsite($website);
            $em->persist($user);
            $em->flush();

            $date = new Date();
            $date->setConnectionToUrl($today->format("Y-m-d H:i:s"));
            $date->addUser($user);
            $date->addWebsite($website);
            $em->persist($date);
            $em->flush();

            $website->setPageView($count + 1);
            $em->persist($website);
            $em->flush();

        }
    }
}