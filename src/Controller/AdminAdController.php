<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
                   ->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Allow to edit an Ad
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a été modifiée avec succès !"
            );
        }

        return $this->render('/admin/ad/edit.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    
    /**
     * Allow to delete an Ad
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "L'annonce <strong>{$ad->getTitle()}</strong> ne peut pas être supprimée ! Des réservations sont en cours !"
            );
        }else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }
        
        return $this->redirectToRoute('admin_ads_index');
    }
}
