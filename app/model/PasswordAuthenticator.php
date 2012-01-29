<?php

use Nette\Security\AuthenticationException;
use Nette\Security\Identity;

class PasswordAuthenticator extends Nette\Object implements \Nette\Security\IAuthenticator
{

	/** @var UserModel */
	private $userModel;

	public function __construct(UserModel $userModel)
	{
		$this->userModel = $userModel;
	}

	/**
	 * Performs an authentication
	 * @param array
	 * @return \Nette\Security\Identity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($mail, $password) = $credentials;
		$user = $this->userModel->findUser(array('mail' => $mail));

		if (!$user) {
			throw new AuthenticationException("User '$mail' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->password !== sha1($password)) {
			throw new AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}

		return $this->userModel->createIdentity($user);
	}

}
