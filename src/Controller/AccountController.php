<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="app_account")
     *
     * @param LoggerInterface $logger
     *
     * @return
     */
    public function index(LoggerInterface $logger): \Symfony\Component\HttpFoundation\Response
    {
        $logger->debug('Checking account page for '.$this->getUser()->getEmail());

        return $this->render('account/index.html.twig', [
        ]);
    }

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            // Va cherche l'annotation main dans le model User
            'groups' => ['main'],
        ]);
    }
}
