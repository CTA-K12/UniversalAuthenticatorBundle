<?php

namespace MESD\UniversalAuthenticatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AuthUser
 */
abstract class AuthUser implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var \DateTime
     */
    private $passwordDate;

    /**
    * @var string $verification
    */
    private $verification;

    /**
    * @var \DateTime $verifyExpiration
    */
    private $verifyExpiration;

    /**
    * @var string $rawPassword
    */
    private $rawPassword;

    /**
    * @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $authRole;

    /**
     * @var \MESD\Security\AuthenticationBundle\Entity\AuthUserService
     */
    private $chosenService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $authUserService;

    /**
     * @var MESD\Security\AuthenticationBundle\Entity\AuthUserSetting
     */
    private $authUserSetting;


    /**
    * Constructor
    */
    public function __construct()
    {
        $this->authRole = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AuthUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
    * Add authRole
    *
    * @param \MESD\Security\AuthenticationBundle\Entity\AuthRole $authRole
    * @return AuthUser
    */
    public function addAuthRole(\MESD\Security\AuthenticationBundle\Entity\AuthRole $authRole)
    {
        $this->authRole[] = $authRole;
        $authRole->addAuthUser($this);

        return $this;
    }

    /**
    * Remove authRole
    *
    * @param \MESD\Security\AuthenticationBundle\Entity\AuthRole $authRole
    */
    public function removeAuthRole(\MESD\Security\AuthenticationBundle\Entity\AuthRole $authRole)
    {
        $this->authRole->removeElement($authRole);
        $authRole->removeAuthUser($this);
    }

    /**
    * Get authRole
    *
    * @return Doctrine\Common\Collections\Collection
    */
    public function getAuthRole()
    {
        return $this->authRole;
    }

    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    public function encodePassword(PasswordEncoderInterface $encoder)
    {
        if ($this->rawPassword) {
            $this->salt = sha1(uniqid(mt_rand(0, 999999999999) . $this->email));
            $this->password = $encoder->encodePassword(
                $this->rawPassword,
                $this->salt
                );
            $this->eraseCredentials();
        }
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return AuthUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set passwordDate
     *
     * @param \DateTime $passwordDate
     * @return AuthUser
     */
    public function setPasswordDate($passwordDate)
    {
        $this->passwordDate = $passwordDate;

        return $this;
    }

    /**
     * Get passwordDate
     *
     * @return \DateTime
     */
    public function getPasswordDate()
    {
        return $this->passwordDate;
    }

    /**
     * Set verification
     *
     * @param string $verification
     * @return AuthUser
     */
    public function setVerification($verification)
    {
        $this->verification = $verification;

        return $this;
    }

    /**
     * Get verification
     *
     * @return string
     */
    public function getVerification()
    {
        return $this->verification;
    }

    /**
     * Set verifyExpiration
     *
     * @param \DateTime $verifyExpiration
     * @return AuthUser
     */
    public function setVerifyExpiration($verifyExpiration)
    {
        $this->verifyExpiration = $verifyExpiration;

        return $this;
    }

    /**
     * Get verifyExpiration
     *
     * @return \DateTime
     */
    public function getVerifyExpiration()
    {
        return $this->verifyExpiration;
    }

    public function getRoles()
    {
        $singleDimension = array();
        foreach($this->authRole as $authRole) {
            $authRole->getRoles($singleDimension);
        }

        return $singleDimension;
    }

    public function eraseCredentials()
    {
        $this->rawPassword = null;
    }

    /**
    * Serializes the content of the current User object
    * @return string
    */
    public function serialize()
    {
        return \json_encode(
            array($this->username, $this->email, $this->password, $this->salt,
                $this->authRole, $this->id));
    }

    /**
    * Unserializes the given string in the current User object
    * @param serialized
    */
    public function unserialize($serialized)
    {
        list($this->username, $this->email, $this->password, $this->salt,
            $this->authRole, $this->id) = \json_decode($serialized);
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AuthUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->username = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    /**
     * Set chosenService
     *
     * @param \MESD\Security\AuthenticationBundle\Entity\AuthUserService $chosenService
     * @return AuthUser
     */
    public function setChosenService(\MESD\Security\AuthenticationBundle\Entity\AuthUserService $chosenService = null)
    {
        $this->chosenService = $chosenService;

        return $this;
    }

    /**
     * Get chosenService
     *
     * @return \MESD\Security\AuthenticationBundle\Entity\AuthUserService
     */
    public function getChosenService()
    {
        return $this->chosenService;
    }

    /**
     * Add authUserService
     *
     * @param \MESD\Security\AuthenticationBundle\Entity\AuthUserService $authUserService
     * @return AuthUser
     */
    public function addAuthUserService(\MESD\Security\AuthenticationBundle\Entity\AuthUserService $authUserService)
    {
        $this->authUserService[] = $authUserService;

        return $this;
    }

    /**
     * Remove authUserService
     *
     * @param \MESD\Security\AuthenticationBundle\Entity\AuthUserService $authUserService
     */
    public function removeAuthUserService(\MESD\Security\AuthenticationBundle\Entity\AuthUserService $authUserService)
    {
        $this->authUserService->removeElement($authUserService);
    }

    /**
     * Get authUserService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthUserService()
    {
        return $this->authUserService;
    }

    /**
     * Add authUserSetting
     *
     * @param MESD\Security\AuthenticationBundle\Entity\AuthUserSetting $authUserSetting
     * @return AuthUser
     */
    public function addAuthUserSetting(\MESD\Security\AuthenticationBundle\Entity\AuthUserSetting $authUserSetting)
    {
        $this->authUserSetting[] = $authUserSetting;

        return $this;
    }

    /**
     * Set authUserSetting
     *
     * @param MESD\Security\AuthenticationBundle\Entity\AuthUserSetting $authUserSetting
     * @return AuthUser
     */
    public function setAuthUserSetting(\MESD\Security\AuthenticationBundle\Entity\AuthUserSetting $authUserSetting = null)
    {
        $this->authUserSetting = $authUserSetting;

        return $this;
    }

    /**
     * Get authUserSetting
     *
     * @return MESD\Security\AuthenticationBundle\Entity\AuthUserSetting
     */
    public function getAuthUserSetting()
    {
        return $this->authUserSetting;
    }
    /**
     * @var string $username
     */
    private $username;


    /**
     * Set username
     *
     * @param string $username
     * @return AuthUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


}