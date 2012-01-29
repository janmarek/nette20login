<?php

class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->template->identity = $this->getUser()->getIdentity();
		}
	}

}
