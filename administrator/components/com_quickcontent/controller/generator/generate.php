<?php
/**
 * Part of joomla3303 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

class QuickcontentControllerGeneratorGenerate extends \Windwalker\Controller\Admin\AbstractRedirectController
{
	/**
	 * Method to run this controller.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->getModel()->saveContent($this->input->get('id'));

		$msg = JText::_('COM_QUICKCONTENT_GENERATE_SUCCESS') ;

		$this->redirect('index.php?option=com_quickcontent', $msg);
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name     The model name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $config   Configuration array for model. Optional.
	 * @param   boolean $forceNew Force get new model, or we get it from cache.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = null, $prefix = null, $config = array(), $forceNew = false)
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true), $forceNew);
	}
}
 