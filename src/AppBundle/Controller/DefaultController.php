<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Setting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $settingsRepo = $this->getDoctrine()->getRepository(Setting::class);

        $companyName =    $settingsRepo->findOneBy(['type' => Setting::COMPANY_NAME_TYPE])->getBody();
        $companyTagline = $settingsRepo->findOneBy(['type' => Setting::COMPANY_TAGLINE_TYPE])->getBody();
        $seoTitle =       $settingsRepo->findOneBy(['type' => Setting::SEO_TITLE_TYPE])->getBody();
        $seoKeywords =    $settingsRepo->findOneBy(['type' => Setting::SEO_KEYWORDS_TYPE])->getBody();
        $seoDescription = $settingsRepo->findOneBy(['type' => Setting::SEO_DESCRIPTION_TYPE])->getBody();

        return $this->render(
            'index.html.twig',
            [
                'companyName'    => $companyName,
                'companyTagline' => $companyTagline,
                'seo' => [
                    'title'       => $seoTitle,
                    'keywords'    => $seoKeywords,
                    'description' => $seoDescription,
                ],
            ]
        );
    }
}
