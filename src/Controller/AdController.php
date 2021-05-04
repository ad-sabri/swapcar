<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Fuel;
use App\Entity\Gearbox;
use App\Entity\Category;
use App\Form\AdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ad", name="ad")
     */
    public function index(): Response
    {
        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
        ]);
    }

    /**
     * @Route("/show", name="ad_show")
     */
    public function show()
    {
        return $this->render('ad/show.html.twig');
    }

    /**
     * @Route("/create", name="ad_create")
     */
    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em)
    {
        //dd($r);

        $builder = $factory->createBuilder(AdType::class, null);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();

            dd($ad);

            $em->persist($ad);

            $em->flush();
        }

        $formView = $form->createView();

        return $this->render('ad/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
