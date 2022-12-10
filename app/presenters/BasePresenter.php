<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nextras\Application\UI\SecuredLinksPresenterTrait;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

	public function authorize()
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->flashMessage('Nejprve se musíte přihlásit', 'alert alert-warning');
			$this->redirect('Sign:in');
		}
	}
}
