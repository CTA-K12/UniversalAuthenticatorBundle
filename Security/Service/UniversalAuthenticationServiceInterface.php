<?php

namespace MESD\UniversalAuthenticatorBundle\Security\Service;

/**
 * UniversalAuthenticationServiceInterface is the interface for
 * all Universal Authentication Services.
 *
 */
interface UniversalAuthenticationServiceInterface
{

    /**
     * Authenticate the user
     */
    public function authenticate();


}