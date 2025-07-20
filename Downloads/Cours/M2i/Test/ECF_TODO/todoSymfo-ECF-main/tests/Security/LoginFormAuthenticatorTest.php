<?php

namespace App\Tests\Security;

use App\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class LoginFormAuthenticatorTest extends TestCase
{
    private UrlGeneratorInterface $urlGenerator;
    private LoginFormAuthenticator $authenticator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->authenticator = new LoginFormAuthenticator($this->urlGenerator);
    }

    public function testAuthenticate(): void
    {
        $request = new Request([], [
            '_username' => 'user@example.com',
            '_password' => 'pass123',
        ]);

        // Création d'une session mockée et association à la requête
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $passport = $this->authenticator->authenticate($request);

        $this->assertNotNull($passport);

        // Récupération sécurisée du badge UserBadge dans le passport
        $userBadges = $passport->getBadges(UserBadge::class);
        $this->assertNotEmpty($userBadges);

        $userBadge = reset($userBadges); // reset() pour éviter l'erreur d'index
        $this->assertInstanceOf(UserBadge::class, $userBadge);

        $this->assertEquals('user@example.com', $userBadge->getUserIdentifier());
    }

    public function testOnAuthenticationSuccessRedirectsToTargetPath(): void
    {
        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $token = $this->createMock(TokenInterface::class);

        // Simulation du target path dans la session (clé utilisée par Symfony)
        $session->set('_security.main.target_path', '/target-path');

        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/target-path', $response->getTargetUrl());
    }

    public function testOnAuthenticationSuccessRedirectsToHomepage(): void
    {
        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $token = $this->createMock(TokenInterface::class);

        // Mock la génération d'URL pour la homepage
        $this->urlGenerator
            ->method('generate')
            ->with('app_home')
            ->willReturn('/');

        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }
}
