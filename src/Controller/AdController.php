<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @IsGranted(
     *  "ROLE_USER",
     *  message="Vous devez être connecté pour pouvoir créer une annonce !"
     * )
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
        
        // Test if form have been submitted and if it's valid
        if($form->isSubmitted() && $form->isValid()){
            // We persist all images which have been added
            foreach($ad->getImages() as $image){
                // We link the image with current ad
                $image->setAd($ad);
                // We persist this image
                $manager->persist($image);
            }

            // Linking with the user which is login
            $ad->setAuthor($this->getUser());

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
     * Allow to implement the edit form of an ad
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security(
     *  "is_granted('ROLE_USER') and user === ad.getAuthor()",
     *  message ="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier !"
     * )
     *
     * @return Response
     */
    public function edit(Request $request, Ad $ad, ObjectManager $manager){
        // Construction a form with the formBuilder
        $form = $this->createForm(AdType::class, $ad);

        // Handle http request in to form
        $form->handleRequest($request);

        // Test if form have been submitted
        if($form->isSubmitted() && $form->isValid()){
            // We persist all images which have been added
            foreach($ad->getImages() as $image){
                // We link the image with current ad
                $image->setAd($ad);
                // We persist this image
                $manager->persist($image);
            }
            // We persit the new ad entity
            $manager->persist($ad);
            dump($ad);

            // We send the sql request in to database in order to save this new ad
            $manager->flush();

            // Sending a flash success which show that the ad have been saving in to database
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a été modifiée avec succès !"
            );
            
            // Redirect to route which show this new ad
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render("ad/edit.html.twig", [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
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

    /**
     * Allow to delete an ad
     *
     * @Route("ads/{slug}/delete", name="ads_delete")
     * @Security(
     *  "is_granted('ROLE_USER') and user === ad.getAuthor()",
     *  message="Vous n'avez pas le droit d'accéder à cette ressource !"
     * )
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        // We ask to manage to remove the ad which have been selectionned
        $manager->remove($ad);

        // We sent the sql request to database
        $manager->flush();
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a été supprimée avec succès"
        );

        return $this->redirectToRoute("ads_index");
    }
    
}
