<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class LoginFormAuthenticator.
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepository;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return bool|void
     */
    public function supports(Request $request)
    {
        // do your work when we're POSTing to the login page
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return \App\Entity\User|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool|void
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // only needed if we need to check a password - we'll do that later!
        return true;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return null|\Symfony\Component\HttpFoundation\Response|void
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('app_homepage'));
    }

    /**
     * Return the URL to the login page.
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        $this->router->generate('app_login');
    }
}
