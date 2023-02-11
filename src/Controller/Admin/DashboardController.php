<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin', name: 'admin_dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('/')]
    #[Route('/dashboard', name: 'show')]
    public function index(): Response
    {
        return $this->render('admin/pages/dashboard.html.twig');
    }
}
