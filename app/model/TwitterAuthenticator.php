<?php

class TwitterAuthenticator
{

	/** @var UserModel */
	private $userModel;

	public function __construct(UserModel $userModel)
	{
		$this->userModel = $userModel;
	}

	/**
	 * @param stdClass $twitterUser
	 * @return \Nette\Security\Identity
	 */
	public function authenticate(stdClass $twitterUser)
	{
		$user = $this->userModel->findUser(array('twitter' => $twitterUser->screen_name));

		if ($user) {
			$this->updateMissingData($user, $twitterUser);
		} else {
			$user = $this->register($twitterUser);
		}

		return $this->userModel->createIdentity($user);
	}

	public function register(stdClass $info)
	{
		$this->userModel->registerUser(array(
			'twitter' => $info->screen_name,
			'name' => $info->name,
		));
	}

	public function updateMissingData($user, stdClass $info)
	{
		if (empty($user['name'])) {
			$this->userModel->updateUser($user, array(
				'name' => $info->name,
			));
		}
	}

}
