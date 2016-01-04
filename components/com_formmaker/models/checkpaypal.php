<?php
 /**
 * @package Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
Defined ('_JEXEC')	or	die();

jimport( 'joomla.application.component.model' );

class formmakerModelcheckpaypal extends JModelLegacy
{

	function checkpaypal()
	{
	
		$input_get = JFactory::getApplication()->input;
	//	$File = "components/com_formmaker/models/request.txt"; 
 	//	$Handle = fopen($File, 'w');
	
		$id=(int)$input_get->getString( 'form_id',0);
		$group_id=(int)$input_get->getString( 'group_id',0);
		
		$form = JTable::getInstance('formmaker', 'Table');
		$form->load( $id);
		


	if($form->checkout_mode=="production")
		$paypal_action="https://www.paypal.com/cgi-bin/webscr";
	else
		$paypal_action="https://www.sandbox.paypal.com/cgi-bin/webscr";
		
	
	$payment_status=$input_get->getString( 'payment_status','');
	
		
	$postdata=""; 
	
	foreach (JRequest::get('post') as $key=>$value) 
		$postdata.=$key."=".urlencode($value)."&"; 
		
	$postdata .= "cmd=_notify-validate"; 
	$curl = curl_init($paypal_action); 
	curl_setopt ($curl, CURLOPT_HEADER, 0); 
	curl_setopt ($curl, CURLOPT_POST, 1); 
	curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata); 
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1); 
	$response = curl_exec ($curl); 
	curl_close ($curl); 
	
		$option			=$input_get->getString('option');
		$total			=$input_get->getString( 'mc_gross');
		$tax_total		=$input_get->getString( 'tax');
		$shipping_total	=$input_get->getString( 'mc_shipping');

		$refresh=0;

		$tax=0;

		$shipping=0;

		$total_cost=0;

		$total_count=0;
		

		$form_currency='$';
		$currency_code=array('USD', 'EUR', 'GBP', 'JPY', 'CAD', 'MXN', 'HKD', 'HUF', 'NOK', 'NZD', 'SGD', 'SEK', 'PLN', 'AUD', 'DKK', 'CHF', 'CZK', 'ILS', 'BRL', 'TWD', 'MYR', 'PHP', 'THB');
		$currency_sign=array('$'  , '€'  , '£'  , '¥'  , 'C$', 'Mex$', 'HK$', 'Ft' , 'kr' , 'NZ$', 'S$' , 'kr' , 'zł' , 'A$' , 'kr' , 'CHF' , 'Kč', '₪'  , 'R$' , 'NT$', 'RM' , '₱'  , '฿'  );
		
		if($form->payment_currency)
			$form_currency=	$currency_sign[array_search($form->payment_currency, $currency_code)];

		$tax=$form->tax;
		$shipping=$input_get->getString( 'mc_shipping',0);

		$db = JFactory::getDBO();

		$query = "UPDATE #__formmaker_submits SET `element_value`='".$db->escape($payment_status)."' WHERE group_id='".$db->escape($group_id)."' AND element_label='0'";

		$db->setQuery( $query);
		$db->query();
		if($db->getErrorNum()){	echo $db->stderr();	return false;}

	

			$row = JTable::getInstance('sessions', 'Table');
			$query = "SELECT id FROM #__formmaker_sessions WHERE group_id=".$db->escape($group_id);
			$db->setQuery( $query);
			$ses_id=$db->LoadResult();
			if($db->getErrorNum()){	echo $db->stderr();	return false;}
			
			if($ses_id)
				$row->load( $ses_id);
			$row->form_id=$id;		
			$row->group_id=$group_id;

			$row->full_name=$input_get->getString( 'first_name','')." ".$input_get->getString( 'last_name','');
			
			$row->email=$input_get->getString( 'payer_email','');

			$row->phone=$input_get->getString( 'night_ phone_a','')."-".$input_get->getString( 'night_ phone_b','')."-".$input_get->getString( 'night_ phone_c','');

			$row->address="Country: ".$input_get->getString( 'address_country','')."<br>";
			if($input_get->getString( 'address_state','')!="")
			$row->address.="State: ".$input_get->getString( 'address_state','')."<br>";
			if($input_get->getString( 'City','')!="")
			$row->address.="City: ".$input_get->getString( 'address_city','')."<br>";
			if($input_get->getString( 'address_street','')!="")
			$row->address.="Street: ".$input_get->getString( 'address_street','')."<br>";
			if($input_get->getString( 'address_zip','')!="")
			$row->address.="Zip Code: ".$input_get->getString( 'address_zip','')."<br>";
			if($input_get->getString( 'address_zip','')!="")
			$row->address.="Address Status: ".$input_get->getString( 'address_status','')."<br>";
			if($input_get->getString( 'address_name','')!="")
			$row->address.="Name: ".$input_get->getString( 'address_name','')."";
			$row->status =$input_get->getString( 'payment_status','');
			
			
			$row->ipn =$response;
			$row->currency =$form->payment_currency.' - '.$form_currency;
			
			$row->paypal_info ="";
			
			
			if($input_get->getString( 'payer_status','')!="")
			$row->paypal_info .= "Payer Status -".$input_get->getString( 'payer_status','')."<br>";
		
			if($input_get->getString( 'payer_email','')!="")
			$row->paypal_info .= "Payer Email -".$input_get->getString( 'payer_email','')."<br>";
		
			if($input_get->getString( 'txn_id','')!="")
			$row->paypal_info .= "Transaction -".$input_get->getString( 'txn_id','')."<br>";
			
			if($input_get->getString( 'payment_type','')!="")
			$row->paypal_info .= "Payment Type -".$input_get->getString( 'payment_type','')."<br>";
			
			if($input_get->getString( 'residence_country','')!="")
			$row->paypal_info .= "Residence Country -".$input_get->getString( 'residence_country','')."<br>";
			
			$row->ord_last_modified = date( 'Y-m-d H:i:s' );

			$row->tax = $tax;

			$row->shipping = $shipping;
			
			$row->total = $total;

		if (!$row->store())
		{	
		
		echo "<script> alert('".$row->getError()."');
			window.history.go(-1); </script>\n";
			exit();
		}
		
		
		
		$list='
		<table class="admintable" border="1" >
	<tr>
		<td class="key">Currency</td>
		<td> '.$row->currency.'</td>
	</tr>
	<tr>
		<td class="key">Date</td>
		<td> '.$row->ord_last_modified.'</td>
	</tr>

	<tr>
		<td class="key">Status</td>
		<td> '.$row->status.'</td>
	</tr>
	<tr>
		<td class="key">Full name</td>
		<td> '.$row->full_name.'</td>
	</tr>
	<tr>
		<td class="key">Email</td>
		<td> '.$row->email.'</td>
	</tr>
	<tr>
		<td class="key">Phone</td>
		<td> '.$row->phone.'</td>
	</tr>
	<tr>
		<td class="key">Mobile phone</td>
		<td> '.$row->mobile_phone.'</td>
	</tr>
	<tr>
		<td class="key">Fax</td>
		<td> '.$row->fax.'</td>
	</tr>
	<tr>
		<td class="key">Address</td>
		<td> '.$row->address.'</td>
	</tr>
	<tr>
		<td class="key">Paypal info</td>
		<td> '.$row->paypal_info.'</td>
	</tr>	
	<tr>
		<td class="key">IPN</td>
		<td> '.$row->ipn.'</td>
	</tr>
	<tr>
		<td class="key">tax</td>
		<td> '.$row->tax.'%</td>
	</tr>
	<tr>
		<td class="key">shipping</td>
		<td> '.$row->shipping.'</td>
	</tr>
	
	<tr>
		<td class="key"><b>Item total</b></td>
		<td> '.($total-$tax_total-$shipping_total).$form_currency.'</td>
	</tr>
	<tr>
		<td class="key"><b>Tax</b></td>
		<td> '.$tax_total.$form_currency.'</td>
	</tr>
	<tr>
		<td class="key"><b>Shipping and handling</b></td>
		<td> '.$shipping_total.$form_currency.'</td>
	</tr>
	<tr>
		<td class="key"><b>Total</b></td>
		<td> '.$total.$form_currency.'</td>
	</tr>
</table>
';

		
		if($form->mail)
		{
										
						
							$config = JFactory::getConfig();
							if($form->mail_from)
							$site_mailfrom = $form->mail_from;
							else
							$site_mailfrom=$config->get( 'config.mailfrom' );										
							
							
											 $from      = $site_mailfrom;
											 $fromname  = ''; 
										
											 $recipient = $form->mail;
											 $cca = $form->mail_cc;
											 $bcc = $form->mail_bcc;
											 $subject   = "Payment information";
											 $body      = $list;
											 $mode      = 1; 

											$send=$this->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cca, $bcc, $attachment, $replyto, $replytoname);  
		
		}

		
	//	fwrite($Handle, $req); 
	//	fclose($Handle); 

		return 0;
	 
	}

	
function sendMail(&$from, &$fromname, &$recipient, &$subject, &$body, &$mode=0, &$cc=null, &$bcc=null, &$attachment=null, &$replyto=null, &$replytoname=null)

    {

			$input_get = JFactory::getApplication()->input;

				$recipient=explode (',', str_replace(' ', '', $recipient ));
				$cc=explode (',', str_replace(' ', '', $cc ));
				$bcc=explode (',', str_replace(' ', '', $bcc ));

                // Get a JMail instance

                $mail = JFactory::getMailer();

 

                $mail->setSender(array($from, $fromname));

                $mail->setSubject($subject);

                $mail->setBody($body);

 

                // Are we sending the email as HTML?

                if ($mode) {

                        $mail->IsHTML(true);

                }

 

                $mail->addRecipient($recipient);

                $mail->addCC($cc);

                $mail->addBCC($bcc);

				

				if($attachment)

					foreach($attachment as $attachment_temp)

					{

						$mail->AddEmbeddedImage($attachment_temp[0], $attachment_temp[1], $attachment_temp[2]);

					}

 

                // Take care of reply email addresses

                if (is_array($replyto)) {

                        $numReplyTo = count($replyto);

                        for ($i=0; $i < $numReplyTo; $i++){

                                $mail->addReplyTo(array($replyto[$i], $replytoname[$i]));

                        }

                } elseif (isset($replyto)) {

                        $mail->addReplyTo(array($replyto, $replytoname));

                }

 

                return  $mail->Send();

        }

	

}





?>