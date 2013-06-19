<?php

/**
 * Test: Nette\Forms\Controls\Checkbox.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 */

use Nette\Forms\Form,
	Nette\Utils\Html;



require __DIR__ . '/../bootstrap.php';


class Translator implements Nette\Localization\ITranslator
{
	function translate($s, $plural = NULL)
	{
		return strtoupper($s);
	}
}



test(function() {
	$form = new Form();
	$input = $form->addCheckbox('on', 'Label');

	Assert::null($input->getLabel());
	Assert::type('Nette\Utils\Html', $input->getControl());
	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" />Label</label>', (string) $input->getControl());
	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" />Another label</label>', (string) $input->getControl('Another label'));

	$input->setValue(TRUE);
	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" checked="checked" />Label</label>', (string) $input->getControl());
});



test(function() { // Html with translator
	$form = new Form();
	$input = $form->addCheckbox('on', 'Label');
	$input->setTranslator(new Translator);

	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" />LABEL</label>', (string) $input->getControl());
	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" />ANOTHER LABEL</label>', (string) $input->getControl('Another label'));
	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" /><b>Another label</b></label>', (string) $input->getControl(Html::el('b', 'Another label')));
});



test(function() { // validation rules
	$form = new Form();
	$input = $form->addCheckbox('on')->setRequired('required');

	Assert::same('<label for="frm-on"><input type="checkbox" name="on" id="frm-on" required="required" data-nette-rules=\'[{"op":":filled","msg":"required"}]\' /></label>', (string) $input->getControl());
});



test(function() { // container
	$form = new Form();
	$container = $form->addContainer('container');
	$input = $container->addCheckbox('on');

	Assert::same('<label for="frm-container-on"><input type="checkbox" name="container[on]" id="frm-container-on" /></label>', (string) $input->getControl());
});
