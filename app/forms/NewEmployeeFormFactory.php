<?php

declare(strict_types=1);


namespace App\Forms;

use _PHPStan_76800bfb5\Nette\Neon\Exception;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Utils\Random;
use Nextras\Orm\Entity\AbstractEntity;
use Tracy\Debugger;
use \Kdyby\Translation\Translator;
use App\Model\UserManager;


class NewEmployeeFormFactory
{
	use SmartObject;

	private $formFactory, $translator, $lang, $userManager;

	public function __construct(FormFactory $formFactory, Translator $translator, UserManager $userManager)
	{
        $this->formFactory = $formFactory;
        $this->userManager=$userManager;
        $this->translator = $translator;
        $this->lang="cs";
        if(substr($_SERVER['REQUEST_URI'], 1, 2)=='en') {
            $this->lang="en";
        }

	}

	public function create(callable $onSuccess, callable $exception)
	{
        $lang="cs";
        if(substr($_SERVER['REQUEST_URI'], 1, 2)=='en') {
            $lang="en";
        }
		$form = $this->formFactory->create();

		$form->addText('name', $this->translator->translate('website.employee.name', NULL, [], NULL, $lang))
			//->setRequired($this->translator->translate('website.employee.nameRequired', NULL, [], NULL, $lang))
			->setHtmlAttribute('placeholder', $this->translator->translate('website.employee.name', NULL, [], NULL, $lang));
        $form->addText('sex', $this->translator->translate('website.employee.sex', NULL, [], NULL, $lang))
            //->setRequired($this->translator->translate('website.employee.sexRequired', NULL, [], NULL, $lang))
            ->setHtmlAttribute('placeholder', $this->translator->translate('website.employee.sex', NULL, [], NULL, $lang));
        $form->addText('age', $this->translator->translate('website.employee.age', NULL, [], NULL, $lang))
            //->setRequired($this->translator->translate('website.employee.ageRequired', NULL, [], NULL, $lang))
            ->setHtmlAttribute('placeholder', $this->translator->translate('website.employee.age', NULL, [], NULL, $lang));
        $form->addProtection($this->translator->translate('website.employee.timeout', NULL, [], NULL, $lang));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $exception) {
            try{
                $this->userManager->add($values);
            }
            catch (\Exception $e){
                $exception($e);
            }
            $onSuccess();
        };
		return $form;

	}
}
