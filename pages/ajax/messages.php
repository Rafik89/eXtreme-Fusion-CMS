<?php/*********************************************************| eXtreme-Fusion 5| Content Management System|| Copyright (c) 2005-2013 eXtreme-Fusion Crew| http://extreme-fusion.org/|| This program is released as free software under the| Affero GPL license. You can redistribute it and/or| modify it under the terms of this license which you| can read by viewing the included agpl.txt or online| at www.gnu.org/licenses/agpl.html. Removal of this| copyright header is strictly prohibited without| written permission from the original author(s).| **********************************************************                ORIGINALLY BASED ON---------------------------------------------------------+| PHP-Fusion Content Management System| Copyright (C) 2002 - 2011 Nick Jones| http://www.php-fusion.co.uk/+--------------------------------------------------------+| Author: Nick Jones (Digitanium)+--------------------------------------------------------+| This program is released as free software under the| Affero GPL license. You can redistribute it and/or| modify it under the terms of this license which you| can read by viewing the included agpl.txt or online| at www.gnu.org/licenses/agpl.html. Removal of this| copyright header is strictly prohibited without| written permission from the original author(s).+--------------------------------------------------------*/require_once '../../system/sitecore.php';try{	if ($_request->post('action')->show() === 'send')	{		if ($_request->post('message_subject')->show())		{			$item_id = $_pdo->getField('SELECT max(`item_id`) FROM [messages]') + 1;			$subject = $_request->post('message_subject')->strip();		}		else		{			$item_id = $_request->post('item_id')->isNum(TRUE);			$subject = '';		}    		if ($_request->post('send')->show() && iUSER && $_request->post('message')->show() != '' && isNum($_request->post('to')->show()))		{			$result = $_pdo->exec('INSERT INTO [messages] (`to`, `from`, `item_id`, `message`, `subject`, `datestamp`) VALUES (:to, :from, :item_id, :message, :subject, :datestamp)',				array(					array(':to', $_request->post('to')->show(), PDO::PARAM_INT),					array(':from', $_user->get('id'), PDO::PARAM_INT),					array(':item_id', $item_id, PDO::PARAM_INT),					array(':subject', $subject, PDO::PARAM_STR),					array(':message', $_request->post('message')->strip(), PDO::PARAM_STR),					array(':datestamp', time(), PDO::PARAM_INT)				)			);		}				if ($_request->post('message_subject')->show())		{			_e($item_id);		}	}	else//if()	{		if (($_request->get('item_id')->show() && $_request->get('item_id')->isNum(TRUE)) || ($_request->post('item_id')->show() && $_request->post('item_id')->isNum(TRUE)))		{			$result = $_pdo->getData('SELECT * FROM [messages] WHERE `item_id` = :item_id AND (`to` = :user OR `from` = :user) ORDER BY id ASC',				array(					array(':item_id',$_request->get('item_id')->show(), PDO::PARAM_INT),					array(':user', $_user->get('id'), PDO::PARAM_INT)				)			);			$i = 0;			$_sbb = $ec->getService('Sbb');			foreach ($result as $row)			{				echo '				<article class="short_post '.($row['from']==$_user->get('id') ? 'light_child' : 'dark_child').' clearfix">					<span class="arrow"></span>					<img src="'.$_user->getAvatarAddr($row['from']).'" alt="Avatar" class="avatar light border_light">					<div class="pw_cont">						<header class="'.($row['from']==$_user->get('id') ? 'light' : 'dark').'">							<time datetime="'.date('c', $row['datestamp']).'" class="text_dark">'.HELP::showDate('longdate', $row['datestamp']).'</time>							<strong class="text_other">'.HELP::profileLink(NULL, $row['from']).' napisał(a):</strong>						</header>						<div class="formated_text">'.$_sbb->parseAllTags($row['message']).'</div>					</div>				</article>';				$i++;			}		}	}}catch(optException $exception){  optErrorHandler($exception);}catch(systemException $exception){  systemErrorHandler($exception);}catch(userException $exception){  userErrorHandler($exception);}catch(PDOException $exception){  PDOErrorHandler($exception);}