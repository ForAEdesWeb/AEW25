<?php
/**
 * @package        Asikart.Plugin
 * @subpackage     system.plg_userxtd
 * @copyright      Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Userxtd\Router\Route;

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Userxtd System Plugin
 *
 * @package        Joomla.Plugin
 * @subpackage     System.userxtd
 */
class PlgSystemUserxtd extends JPlugin
{
	/**
	 * Property self.
	 *
	 * @var PlgSystemUserxtd
	 */
	public static $self;

	/**
	 * Property container.
	 *
	 * @var  \Windwalker\DI\Container
	 */
	protected $container = null;

	/**
	 * Property app.
	 *
	 * @var  \JApplicationCms
	 */
	protected $app = null;

	/**
	 * Property input.
	 *
	 * @var  JInput
	 */
	protected $input = null;

	/**
	 * Property user_data.
	 *
	 * @var array
	 */
	protected $user_data;

	/**
	 * Constructor
	 *
	 * @access      public
	 *
	 * @param       object $subject The object to observe
	 * @param       array  $config  An array that holds the plugin configuration
	 *
	 * @since       1.6
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		$this->input = $this->app->input;

		// Allow Context
		$this->allow_context = array(
			'com_users.profile',
			'com_users.user',
			'com_users.registration',
			'com_admin.profile',
			'com_userxtd.profile',
			'com_userxtd.user',
			'com_userxtd.registration'
		);

		$UXParams = JComponentHelper::getParams('com_userxtd');
		$this->params->merge($UXParams);

		self::$self = $this;

		// Autoloading
		\JLoader::registerNamespace('Userxtd', __DIR__ . '/src');
	}

	/**
	 * getInstance
	 *
	 * @return  PlgSystemUserxtd
	 */
	public static function getInstance()
	{
		return self::$self;
	}


	// System Events
	// ======================================================================================

	/**
	 * Converting the site URL to fit to the HTTP request
	 */
	public function onAfterRoute()
	{
		$params = JComponentHelper::getParams('com_userxtd');

		if ($params->get('CoreRegistration_Redirect', 0))
		{
			$option = $this->input->get('option');
			$view   = $this->input->get('view');
			$layout = $this->input->get('layout', 'default');
			$id     = $this->input->get('id', JFactory::getUser()->id);

			if ($option == 'com_users')
			{
				$this->initComponent();

				if ($view == 'registration' && $layout == 'default')
				{
					$this->app->redirect(Route::_('register'));
				}

				if ($view == 'profile' && $layout == 'default')
				{
					if ($id)
					{
						$this->app->redirect(Route::_('user_id', array('id' => $id)));
					}
					else
					{
						$this->app->redirect(Route::_('user'));
					}
				}

				if ($view == 'profile' && $layout == 'edit')
				{
					$this->app->redirect(Route::_('user_layout', array('task' => 'user.edit.edit', 'layout' => 'edit', 'id' => $id)));
				}
			}
		}
	}

	// Content Events
	// ======================================================================================

	/**
	 * Userxtd prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param   string  $context  The context of the content being passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  void
	 */
	public function onContentPrepare($context, $article, $params, $page = 0)
	{
	}

	/**
	 * Userxtd after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string  $context  The context of the content being passed to the plugin.
	 * @param   object  $article  The content object.  Note $article->text is also available
	 * @param   object  $params   The content params
	 * @param   int     $page     The 'page' number
	 *
	 * @return  string
	 */
	public function onContentAfterDisplay($context, $article, $params, $page = 0)
	{
		$result = '';

		if ($this->params->get('UserInfo', 1))
		{
			// Detect Context
			$option = $this->input->get('option');
			$view 	= $this->input->get('view');
			$layout = $this->input->get('layout', 'default');

			if( $option == 'com_content' && $view == 'article' && $layout == 'default' )
			{
				$this->initComponent();

				$result .= \Userxtd\Content\UserInfo::createInfoBox($this->getContainer(), $article, $params);
			}
		}

		return $result;
	}

	/**
	 * Userxtd before save content method
	 *
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 * You can set the error by calling $article->setError($message)
	 *
	 * @param   string  $context  The context of the content passed to the plugin.
	 * @param   object  $article  A JTableContent object
	 * @param   bool    $isNew    If the content is just about to be created
	 *
	 * @return  bool  If false, abort the save
	 */
	public function onContentBeforeSave($context, $article, $isNew = false)
	{
		return true;
	}

	/**
	 * Userxtd after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param   string  $context  The context of the content passed to the plugin.
	 * @param   object  $article  A JTableContent object
	 * @param   bool    $isNew    If the content is just about to be created
	 *
	 * @return  void
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
		return;
	}

	// Form Events
	// ====================================================================================

	/**
	 * @param   JForm $form The form to be altered.
	 * @param   array $data The associated data for the form.
	 *
	 * @return  boolean
	 */
	public function onContentPrepareForm($form, $data)
	{
		// Check we are manipulating a valid form.
		$name = $form->getName();

		if (!in_array($name, $this->allow_context))
		{
			return true;
		}

		// Include UserXTD core API.
		$this->initComponent();

		$result = null;
		$UXParams = JComponentHelper::getParams('com_userxtd');

		// Prepare Data
		// ============================================================================

		// Set Chosen
		JHtmlFormbehavior::chosen('select');

		// Prepare Form
		// ============================================================================

		// Get Form
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		// Hide some fields in registration
		$context = $this->getContext();
		$array = array();
		$reg_context = array('com_users.registration', 'com_userxtd.registration');
		$profile_context = array('com_users.profile');

		$catid = null;

		if (in_array($context, $reg_context) || $this->get('hide_registration_field'))
		{
			$array['hide_in_registration'] = true;

			$catid = $UXParams->get('CoreRegistration_Categories', array('*'));
		}
		elseif (in_array($context, $profile_context))
		{
			$catid = $UXParams->get('CoreRegistration_Categories_InUserInfo', array('*'));
		}

		// Set category
		if (!is_array($catid))
		{
			$catid = array($catid);
		}

		if (!in_array('*', $catid))
		{
			$catid = implode(',', $catid);
		}
		else
		{
			$catid = null;
		}

		$form = \Userxtd\Form\FormHelper::getFieldsByCategory($catid, $form, $array);

		return $result;
	}



	// User Events
	// ====================================================================================

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 *
	 * @param    array   $user    Holds the new user data.
	 * @param    boolean $isnew   True if a new user is stored.
	 * @param    boolean $success True if user was succesfully stored in the database.
	 * @param    string  $msg     Message.
	 *
	 * @return   boolean
	 */
	public function onUserBeforeSave($user, $isnew, $success, $msg = null)
	{
		return true;
	}

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * @param    array   $data    Holds the new user data.
	 * @param    boolean $isNew   True if a new user is stored.
	 * @param    boolean $result  True if user was succesfully stored in the database.
	 * @param    string  $error   Message.
	 *
	 * @return   void
	 */
	public function onUserAfterSave($data, $isNew, $result, $error = null)
	{
		$db = JFactory::getDbo();

		// don't do anything when activate.
		$allowTask = array('save', 'apply', 'save2new', 'user.edit.save');

		if (! in_array($this->input->get('task'), $allowTask))
		{
			return;
		}

		// Init Framework
		// ===============================================================
		$this->initComponent();

		$UXParams = \Windwalker\System\ExtensionHelper::getParams('com_userxtd');

		// For Upload Event
		$this->user_data = $data;

		// Set Category
		$catid = $UXParams->get('CoreRegistration_Categories', array('*'));

		if (!is_array($catid))
		{
			$catid = array($catid);
		}

		if (!in_array('*', $catid))
		{
			$catid = implode(',', $catid);
		}
		else
		{
			$catid = null;
		}

		// Get Data and handle them
		$form = \Userxtd\Form\FormHelper::getFieldsByCategory($catid);

		$form->bind($data);

		$data['profile'] = $form->getDataForSave('profile');
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		// Start Building query
		// ===============================================================
		if ($userId && $result && isset($data['profile']) && (count($data['profile'])))
		{
			try
			{
				//Sanitize the date
				// ===============================================================
				if (!empty($data['profile']['dob']))
				{
					$date = new JDate($data['profile']['dob']);

					$data['profile']['dob'] = $date->format('Y-m-d');
				}

				$query = $db->getQuery(true);

				$query->delete('#__userxtd_profiles')
					->where('user_id = ' . $userId);

				$db->setQuery($query)->execute();

				$tuples = array();
				$order  = 1;

				$query = $db->getQuery(true);

				$query->columns(
					array(
						$query->qn('user_id'),
						$query->qn('key'),
						$query->qn('value'),
						$query->qn('ordering')
					)
				);

				// Build query
				// ===============================================================
				foreach ($data['profile'] as $k => $v)
				{
					if (is_array($v) || is_object($v))
					{
						$v = implode(',', (array) $v);
					}

					$query->values($userId . ', ' . $query->quote($k) . ', ' . $query->quote($v) . ', ' . $order++);
				}

				$query->insert('#__userxtd_profiles');

				$db->setQuery($query)->execute();

			} catch (RuntimeException $e)
			{
				$this->_subject->setError($e->getMessage());

				return;
			}
		}

		return;
	}

	/**
	 * onCCKEngineAfterFormLoad
	 *
	 * @param \JForm            $form
	 * @param array             $data
	 * @param \JFormField       $formField
	 * @param \SimpleXMLElement $element
	 * @param boolean           $form_setted
	 *
	 * @return  void
	 */
	public function onCCKEngineAfterFormLoad($form = null, $data = null, $formField = null, $element = null, $form_setted = false)
	{
		// Add Hide reg field
		$form->loadFile(dirname(__FILE__) . '/form/forms/fields.xml');

		// label do not required
		$form->setFieldAttribute('label', 'required', 'false');
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param    array $user    Holds the user data
	 * @param    array $options Array holding options (remember, autoregister, group)
	 *
	 * @return   boolean    True on success
	 */
	public function onUserLogin($user, $options = array())
	{
		return true;
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param    array $user    Holds the user data.
	 * @param    array $options Array holding options (client, ...).
	 *
	 * @return   boolean True on success
	 */
	public function onUserLogout($user, $options = array())
	{
		return true;
	}

	/**
	 * Utility method to act on a user before it has been saved.
	 *
	 *
	 * @param    array   $user    Holds the new user data.
	 * @param    boolean $isnew   True if a new user is stored.
	 * @param    boolean $success True if user was succesfully stored in the database.
	 * @param    string  $msg     Message.
	 *
	 * @return   boolean
	 */
	public function onUserBeforeDelete($user, $isnew, $success, $msg)
	{
		return true;
	}

	/**
	 * Remove all sessions for the user name
	 *
	 * @param    array   $user    Holds the user data
	 * @param    boolean $success True if user was succesfully stored in the database
	 * @param    string  $msg     Message
	 *
	 * @return   boolean
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			// return false;
		}

		$userId = JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = \JFactory::getDbo();
				$query = $db->getQuery(true);

				$query->delete('#__userxtd_profiles')
					->where('user_id = ' . $userId);

				$db->setQuery($query)->execute();
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}

		return true;
	}

	/**
	 * On content prepare data.
	 *
	 * @param    string $context The context for the data
	 * @param    int    $data    The user id
	 *
	 * @return   boolean
	 */
	public function onContentPrepareData($context, $data)
	{
		$this->initComponent();

		$params = JComponentHelper::getParams('com_userxtd');

		JHtml::register('users.uploadimage', array('\\Userxtd\\Form\\FieldDisplay', 'showImage'));

		// Check we are manipulating a valid form.
		if (!in_array($context, $this->allow_context))
		{
			return true;
		}

		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0)
			{
				// Load the profile data from the database.
				// ===============================================================
				$db = JFactory::getDbo();
				$query  = $db->getQuery(true);

				// Filter categories
				$cats = (array) $params->get('CoreRegistration_Categories_InUserInfo', array('*'));

				if (!in_array('*', $cats))
				{
					$query->where('b.catid IN (' . implode(',', $cats) . ')');
				}

				$query->select("a.key, a.value, b.field_type, b.attrs")
					->from("#__userxtd_profiles AS a")
					->leftJoin('#__userxtd_fields AS b ON a.key=b.name')
					->where('a.user_id = ' . (int) $userId)
					->order("b.ordering");

				try
				{
					$results = $db->setQuery($query)->loadRowList();
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());

					return false;
				}

				// Merge the profile data.
				// ===============================================================
				$data->profile = array();

				foreach ($results as $v)
				{
					$k = $v[0];

					// Convert String to Array if multiple
					$v[3] = json_decode($v[3], true);

					if (JArrayHelper::getValue($v[3], 'multiple'))
					{
						$v[1] = explode(',', $v[1]);
					}

					// merge data
					$data->profile[$k] = $v[1];

					if ($data->profile[$k] === null)
					{
						$data->profile[$k] = $v[1];
					}
				}
			}
		}

		return true;
	}

	/**
	 * onCCKEngineUploadImage
	 *
	 * @param   string            &$url
	 * @param   \JFormField       $field
	 * @param   \SimpleXmlElement $element
	 *
	 * @return  void
	 */
	public function onCCKEngineUploadImage(&$url, $field, $element = null)
	{
		$input = \JFactory::getApplication()->input;
		$data = $this->user_data ? : (array) JFactory::getUser($input->get('id'));

		$name = $_FILES['jform']['name']['profile'][$field->fieldname . '_upload'];
		$name = explode('.', $name);
		$ext  = array_pop($name);

		if ($data && $url)
		{
			$url = "images/userxtd/{$data['username']}/" . $field->fieldname . '.' . $ext;
		}
	}

	/**
	 * getContext
	 *
	 * @return  string
	 */
	public function getContext()
	{
		$option  = $this->input->get('option');
		$view    = $this->input->get('view');
		$context = "{$option}.{$view}";

		return $context;
	}

	/**
	 * initComponent
	 *
	 * @return  void
	 */
	public function initComponent()
	{
		include_once JPATH_ADMINISTRATOR . '/components/com_userxtd/src/init.php';
	}

	/**
	 * getService
	 *
	 * @param string  $key
	 * @param boolean $forceNew
	 *
	 * @return  mixed
	 */
	protected function getService($key, $forceNew = false)
	{
		return $this->getContainer()->get($key, $forceNew);
	}

	/**
	 * getContainer
	 *
	 * @return  \Windwalker\DI\Container
	 */
	public function getContainer()
	{
		if (! $this->container)
		{
			$this->initComponent();

			$this->container = \Windwalker\DI\Container::getInstance('com_userxtd');
		}

		return $this->container;
	}

	/**
	 * setContainer
	 *
	 * @param   \Windwalker\DI\Container $container
	 *
	 * @return  PlgSystemUserxtd  Return self to support chaining.
	 */
	public function setContainer($container)
	{
		$this->container = $container;

		return $this;
	}
}
