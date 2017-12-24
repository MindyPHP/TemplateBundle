<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\VariableProvider;

use Mindy\Template\VariableProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AppVariableProvider.
 */
class AppVariableProvider implements VariableProviderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * AppVariableProvider constructor.
     *
     * @param RequestStack          $requestStack
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(RequestStack $requestStack, TokenStorageInterface $tokenStorage = null)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            // TODO deprecate since 5.0
            'user' => $this->getUser(),

            'current_user' => $this->getUser(),
            'request' => $this->request,
        ];
    }

    /**
     * @return null|UserInterface
     */
    protected function getUser()
    {
        if (null === $this->tokenStorage) {
            return null;
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
