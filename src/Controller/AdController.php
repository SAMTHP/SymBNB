<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        // We get all ads
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Allow to create an Ad
     * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        // Instanciation of a new ad
        $ad = new Ad();

        // Construction a form with the formBuilder
        $form = $this->createForm(AdType::class, $ad);

        // Handle http request in to form
        $form->handleRequest($request);
        
        // Test if form have been submitted
        if($form->isSubmitted() && $form->isValid()){
            // We persit the new ad entity
            $manager->persist($ad);
            dump($ad);

            // We send the sql request in to database in order to save this new ad
            $manager->flush();

            // Sending a flash success which show that the ad have been saving in to database
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a été créée avec succès !"
            );
            
            // Redirect to route which show this new ad
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        // Return in to a view and give to twig the variable form which will create the form template
        return $this->render(
            'ad/new.html.twig',
            ['form' => $form->createView()]
        );

    }

    /**
     * Allow to show an ad
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render(
                "ad/show.html.twig",
                [
                    'ad' => $ad
                ]
            );
    }

    
}
