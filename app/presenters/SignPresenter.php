<?php

use Nette\Application\UI\Form;
use Nette\Security\IIdentity;

class SignPresenter extends BasePresenter
{

	public function actionIn()
	{
		// facebook
		$fbUrl = $this->context->facebook->getLoginUrl(array(
			'scope' => 'email',
			'redirect_uri' => $this->link('//fbLogin'), // absolute
		));

		// twitter
		$twitter = $this->context->twitter;
		$token = $twitter->getRequestToken();
		$twitterSession = $this->getSession('twitter');
		$twitterSession->oauthToken = $token['oauth_token'];
		$twitterSession->oauthTokenSecret = $token['oauth_token_secret'];
		$twitterUrl = $twitter->getAuthorizeURL($token);

		$this->template->fbUrl = $fbUrl;
		$this->template->twitterUrl = $twitterUrl;
	}

	public function actionFbLogin()
	{
		$me = $this->context->facebook->api('/me');
		$identity = $this->context->facebookAuthenticator->authenticate($me);

		$this->getUser()->login($identity);
		$this->redirect('Homepage:');
	}

	public function actionTwitter()
	{
		$twitterConfig = $this->context->parameters['twitter'];
		$twitterSession = $this->getSession('twitter');

		$twitter = new TwitterOAuth(
			$twitterConfig['consumerKey'],
			$twitterConfig['consumerSecret'],
			$twitterSession->oauthToken,
			$twitterSession->oauthTokenSecret
		);

		$accessToken = $twitter->getAccessToken();
		$info = $twitter->get('/users/show', array(
			'user_id' => $accessToken['user_id'],
		));

		$authenticator = $this->context->twitterAuthenticator;
		$identity = $authenticator->authenticate($info);

		$this->getUser()->login($identity);
	}

	protected function createComponentSignInForm()
	{
		$form = new Form;
		$form->addText('mail', 'Mail')
			->setRequired('Vyplňte e-mail.');

		$form->addPassword('password', 'Heslo')
			->setRequired('Vyplňte heslo');

		$form->addSubmit('s', 'Přihlásit se');

		$form->onSuccess[] = callback($this, 'signInFormSubmitted');
		return $form;
	}

	public function signInFormSubmitted($form)
	{
		try {
			$values = $form->getValues();
			$user = $this->getUser();
			$user->login($values->mail, $values->password);
			$this->redirect('Homepage:');

		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Uživatel byl odhlášen.');
		$this->redirect('Homepage:');
	}

}
