<?php

namespace Umbrella\AdminBundle\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\AdminBundle\Model\AdminUserInterface;

/**
 * Class UserMailer
 */
class UserMailer
{
    protected Environment $twig;
    protected RouterInterface $router;
    protected \Swift_Mailer $mailer;
    protected ParameterBagInterface $parameters;

    /**
     * UserMailer constructor.
     */
    public function __construct(Environment $twig, RouterInterface $router, \Swift_Mailer $mailer, ParameterBagInterface $parameters)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->parameters = $parameters;
    }

    public function sendPasswordRequestEmail(AdminUserInterface $user): void
    {
        $message = new \Swift_Message();
        $message
            ->setSubject('Changement de mot de passe')
            ->setFrom($this->parameters->get('umbrella_admin.user.mailer.from_email'), $this->parameters->get('umbrella_admin.user.mailer.from_name'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('@UmbrellaAdmin/Mail/password_request.html.twig', [
                    'user' => $user,
                    'reset_url' => $this->router->generate('umbrella_admin_security_passwordreset', ['token' => $user->getConfirmationToken()], UrlGenerator::ABSOLUTE_URL),
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
