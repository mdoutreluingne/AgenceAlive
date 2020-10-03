<?php

namespace App\Controller\Admin;

use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminLogController extends AbstractController
{
    /**
     * @Route("/admin/log", name="admin.log")
     */
    public function index()
    {
        $logRepository = $this->getDoctrine()->getRepository(Log::class);
        $AllLogs = $logRepository->findLastAllLog();
        return $this->render('admin_log/index.html.twig', [
            'current_menu' => 'logs',
            'AllLogs' => $AllLogs,
        ]);
    }
}
