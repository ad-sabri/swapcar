<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    protected $userRepository;
    protected $adRepository;


    public function __construct(
        UserRepository $userRepository,
        AdRepository $adRepository
    ) {
        $this->userRepository = $userRepository;
        $this->adRepository = $adRepository;
    }
    /**
     * @Route("/admin", name="admin") 
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'allAds' => $this->adRepository->countAllAds(),
            'allUsers' => $this->userRepository->countAllUsers(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration Swapcar');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Annonces', 'fas fa-car-side', Ad::class);
        yield MenuItem::linkToCrud('Membres', 'fas fa-user', User::class);
    }
}
