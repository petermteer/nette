<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Forms\Controls;

use Nette,
	Nette\Utils\Html;



/**
 * Set of radio button controls.
 *
 * @author     David Grudl
 *
 * @property   array $items
 * @property-read Nette\Utils\Html $separatorPrototype
 * @property-read Nette\Utils\Html $containerPrototype
 */
class RadioList extends BaseControl
{
	/** @var Nette\Utils\Html  separator element template */
	protected $separator;

	/** @var Nette\Utils\Html  container element template */
	protected $container;

	/** @var array */
	protected $items = array();



	/**
	 * @param  string  label
	 * @param  array   options from which to choose
	 */
	public function __construct($label = NULL, array $items = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'radio';
		$this->container = Html::el();
		$this->separator = Html::el('br');
		if ($items !== NULL) {
			$this->setItems($items);
		}
	}



	/**
	 * Returns selected radio value.
	 * @return mixed
	 */
	public function getValue($raw = FALSE)
	{
		if ($raw) {
			trigger_error(__METHOD__ . '(TRUE) is deprecated; use getRawValue() instead.', E_USER_DEPRECATED);
		}
		$value = $this->getRawValue();
		return ($raw || isset($this->items[$value])) ? $value : NULL;
	}



	/**
	 * Returns selected radio value (not checked).
	 * @return mixed
	 */
	public function getRawValue()
	{
		if (is_scalar($this->value)) {
			$foo = array($this->value => NULL);
			return key($foo);
		}
	}



	/**
	 * Has been any radio button selected?
	 * @return bool
	 */
	public function isFilled()
	{
		return $this->getValue() !== NULL;
	}



	/**
	 * Sets options from which to choose.
	 * @param  array
	 * @param  bool
	 * @return RadioList  provides a fluent interface
	 */
	public function setItems(array $items, $useKeys = TRUE)
	{
		$this->items = $useKeys ? $items : array_combine($items, $items);
		return $this;
	}



	/**
	 * Returns options from which to choose.
	 * @return array
	 */
	final public function getItems()
	{
		return $this->items;
	}



	/**
	 * Returns separator HTML element template.
	 * @return Nette\Utils\Html
	 */
	final public function getSeparatorPrototype()
	{
		return $this->separator;
	}



	/**
	 * Returns container HTML element template.
	 * @return Nette\Utils\Html
	 */
	final public function getContainerPrototype()
	{
		return $this->container;
	}



	/**
	 * Generates control's HTML element.
	 * @param  mixed
	 * @return Nette\Utils\Html
	 */
	public function getControl($key = NULL, $caption = NULL)
	{
		$selectedValue = $this->value === NULL ? NULL : (string) $this->getValue();
		$control = parent::getControl();
		$container = clone $this->container;
		$separator = (string) $this->separator;
		$items = $key === NULL ? $this->items : array($key => $this->items[$key]);

		foreach ($items as $k => $v) {
			$control->checked = (string) $k === $selectedValue;
			$control->value = $k;
			$label = parent::getLabel($caption === NULL ? $v : $caption);
			$label->insert(0, $control);
			$control->id = $label->for .= '-' . $k;
			if ($key !== NULL) {
				return $label;
			}

			$container->add((string) $label . $separator);
			$control->data('nette-rules', NULL);
		}

		return $container;
	}



	/**
	 * Generates label's HTML element.
	 * @param  string
	 * @param  mixed
	 * @return void
	 */
	public function getLabel($caption = NULL)
	{
		return parent::getLabel($caption)->for(NULL);
	}

}
