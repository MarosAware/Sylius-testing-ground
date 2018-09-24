<?php

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class SecurityController extends \Sylius\Bundle\UserBundle\Controller\SecurityController
{
    public function loginAction(Request $request): Response
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $options = $request->attributes->get('_sylius');

//        $template = $options['template'] ?? null;
//        Assert::notNull($template, 'Template is not configured.');
//
//        $formType = $options['form'] ?? UserLoginType::class;
//        $form = $this->get('form.factory')->createNamed('', $formType);

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('shop_authenticate');

        return $this->render('@App/testLogin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'token' => $token,
        ]);
    }
}
