<?php
/**
 * ------------------------------------------------------------------------
 * JA Mitius Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<ul>
<?php if($type == 'logout') : ?>
	<li>
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="form-login" id="login-form" class="clearfix">
	<div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
	</div>
	<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if($params->get('name') == 0) :
		echo JText::sprintf('HINAME', $user->get('username'));
	 else :
		echo JText::sprintf('HINAME', $user->get('name'));
	 endif; ?>
	</div>
	<?php endif; ?>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_('form.token');?>
</form>
	</li>
<?php else : ?>
	<li class="first">
		<a class="login-switch" href="<?php echo JRoute::_('index.php?option=com_user&view=login');?>" onclick="showBox('ja-user-login','mod_login_username',this, window.event || event);return false;" title="<?php echo JText::_('TXT_LOGIN');?>"><span><?php echo JText::_('TXT_LOGIN');?></span></a>

	<!--LOFIN FORM content-->
	<div id="ja-user-login" style="width:240px;">
	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
        <?php JHTML::_('script', 'openid.js'); ?>
    <?php endif; ?>
	  <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="form-login" id="login-form" >
			<div class="pretext">
				<?php echo $params->get('pretext'); ?>
			</div>
			<fieldset class="userdata">
				<p id="form-login-username">
					<label for="modlgn-username"><?php echo JText::_('JAUSERNAME') ?></label>
					<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
				</p>
				<p id="form-login-password">
					<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
				</p>
				<?php if (!is_null($tfa) && $tfa != array()):?>
				<p class="login-input secretkey">
					<label class="" for="secretkey" id="secretkey-lbl" aria-invalid="false"><?php echo JText::_('JASECRETKEY') ?></label>
					<input type="text" size="25" value="" id="secretkey" name="secretkey">
				</p>
				<?php endif; ?>
				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<p id="form-login-remember">
					<label for="modlgn-remember"><?php echo JText::_('JAREMEMBER_ME') ?></label>
					<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
				</p>
				<?php endif; ?>
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JABUTTON_LOGIN'); ?>" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHTML::_('form.token'); ?>
			</fieldset>
			<ul>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
					<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
				</li>
				<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
						<?php echo JText::_('JAREGISTER'); ?></a>
				</li>
				<?php endif; ?>
			</ul>
	        <?php echo $params->get('posttext'); ?>
	    </form>
    </div>

	</li>
	<?php
				$option = JRequest::getCmd('option');
				$task = JRequest::getCmd('task');
				if($option!='com_user' && $task != 'register') { ?>
	<li>
		 <?php if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') != 0): ?>
		<a class="register-switch" href="<?php echo JRoute::_("index.php?option=com_users&task=registration");?>" onclick="showBox('ja-user-register','namemsg',this, window.event || event);return false;" >
			<span><?php echo JText::_('JAREGISTER');?></span>
		</a>
		<?php endif ?>
		<!--LOFIN FORM content-->
		<div id="ja-user-register" <?php if(!empty($captchatext)) echo "class='hascaptcha'"; ?>  style="width:240px;">
			<?php
			JHTML::_('behavior.keepalive');
			JHTML::_('behavior.formvalidation');
			?>

			<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
				<fieldset>
				<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
					<legend><?php echo JText::_($fieldset->label);?></legend>
				<?php endif;?>
					<dl>
							<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
							<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Name</strong><br />Enter your full name"><?php echo JText::_( 'JANAME' ); ?>:</label><em>*</em></dt>
							<?php } else { ?>
							<label  class="hasTip required" for="jform_name" id="jform_name-lbl" title="Name::Enter your full name"><?php echo JText::_( 'JANAME' ); ?>:</label><em>*</em></dt>
							<?php } ?>
							<dd><input type="text" size="30" class="required" value="" id="jform_name" name="jform[name]"/></dd>

							<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
								<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Username</strong><br />Enter your user name"><?php echo JText::_( 'JAUSERNAME' ); ?>:</label><em>*</em></dt>
								<?php } else { ?>
								<label title="Username::Enter your user name" class="hasTip required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JAUSERNAME' ); ?>:</label><em>*</em></dt>
							<?php } ?>
								
							<dd><input type="text" size="30" class="validate-username required" value="" id="jform_username" name="jform[username]"/>
							</dd>
							<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
								<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Password</strong><br />Enter your password"><?php echo JText::_( 'JGLOBAL_PASSWORD' ); ?>:</label><em>*</em></dt>
								<?php } else { ?>
								<label title="Password::Enter your password" class="hasTip required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JGLOBAL_PASSWORD' ); ?>:</label><em>*</em></dt>
							<?php } ?>
								
						<dd>
							<input type="password" size="30" class="validate-password required" value="" id="jform_password1" name="jform[password1]" />
						</dd>
						<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
								<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Password</strong><br />Enter your confirm password"><?php echo JText::_( 'JGLOBAL_REPASSWORD' ); ?>:</label><em>*</em></dt>
								<?php } else { ?>
								<label title="Password::Enter your confirm password" class="hasTip required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JGLOBAL_REPASSWORD' ); ?>:</label><em>*</em></dt>
							<?php } ?>
							
						<dd>

							<input type="password" size="30" class="validate-password required" value="" id="jform_password2" name="jform[password2]" />
						</dd>
						<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
								<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Email</strong><br />Enter your email"><?php echo JText::_( 'JAEMAIL' ); ?>:</label><em>*</em></dt>
								<?php } else { ?>
								<label title="Email::Enter your email" class="hasTip required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JAEMAIL' ); ?>:</label><em>*</em></dt>
							<?php } ?>
						<dd>
							<input type="text" size="30" class="validate-email required" value="" id="jform_email1" name="jform[email1]" />
						</dd>
						<dt>
							<?php if (version_compare(JVERSION, '3.0', 'ge')){ ?>
								<label  class="hasTooltip required" for="jform_name" id="jform_name-lbl" title="<strong>Email</strong><br />Enter your confirm email"><?php echo JText::_( 'JACONFIRM_EMAIL_ADDRESS' ); ?>:</label><em>*</em></dt>
								<?php } else { ?>
								<label title="Email::Enter your confirm email" class="hasTip required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JACONFIRM_EMAIL_ADDRESS' ); ?>:</label><em>*</em></dt>
							<?php } ?>
							
						<dd>
							<input type="text" size="30" class="validate-email required" value="" id="jform_email2" name="jform[email2]" />
						</dd>
					    <?php  if(!empty($captchatext)) { ?>
						<dt>

							<label title="" class="hasTip hasTooltip required"  id="jform_captcha-lbl"><?php echo JText::_( 'JACAPTCHA'); ?>:</label>			        </dt>
						<dd>
							<?php echo $captchatext; ?> <br>*
						</dd>
						<?php } ?>
					</dl>
				</fieldset>
				<p><?php echo JText::_("DESC_REQUIREMENT"); ?></p>
				<button type="submit" class="validate"><?php echo JText::_('JAREGISTER');?></button>
				<div>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="registration.register" />
					<?php echo JHTML::_('form.token');?>
				</div>
			</form>
				<!-- Old code -->
		</div>
	</li>
	<?php } ?>
		<!--LOFIN FORM content-->
<?php endif; ?>
</ul>