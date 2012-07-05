<?php
/************************************************************************************
 mod_aidanews for Joomla 1.5 by Danilo A.

 @author: Danilo A. - dan@cdh.it

 ----- This file is part of the AiDaNews Module. -----

    AiDaNews Module is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    AiDaNews is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this module.  If not, see <http://www.gnu.org/licenses/>.
************************************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?><?php 

// ---------------------------- Variables ----------------------------

//Main variables
$Config_live_site 		= JURI::base();
$db		 				=& JFactory::getDBO();
$my						=& JFactory::getUser();
$access 				= !JApplication::getCfg('shownoauth');
//FLEXIcheck - Lets the module check if FLEXIcontent has been installed on the site
$flexicheck				= 'components/com_flexicontent/index.html';
if (file_exists($flexicheck)) {
	$fc = 1;
}else{
	$fc = 0;
}

//Language Strings (and other variables related to language)
$fish = $params->get('fishsupport', 0);
if ($fc) {
	if (($fish == 1) || ($fish == 2)) {
		$lingua					= JRequest::getWord('lang', '' );
		$limitlang				= $params->get('limitlang');
	}
}
$nothingtoshow			= $params->get('nothingtoshow');
$hittitle_S				= $params->get('hit_title_S');
$hittitle_P				= $params->get('hit_title_P');
$hitprefix 				= $params->get('hit_prefix');
$ratingtitle_S 			= $params->get('rating_title_S');
$ratingtitle_P 			= $params->get('rating_title_P');
$ratingprefix 			= $params->get('rating_prefix');
$commenttitle_S			= $params->get('comment_title_S');
$commenttitle_P			= $params->get('comment_title_P');
$commentprefix 			= $params->get('comment_prefix');
$authorprefix			= $params->get('auth_prefix');
$catprefix 				= $params->get('cat_prefix');
$dateprefix 			= $params->get('date_prefix');
$addcomm				= $params->get('addcomm');
$fbshare				= $params->get('fbshare');

//Related Items Variables
if (($params->get('related') == 1) || ($params->get('flexirelated') == 1) || ($params->get('flexirelated') == 2)) {
	$temp				= JRequest::getString('id');
	$temp				= explode(':', $temp);
	$id					= $temp[0];
	$relatednoid		= $params->get('relatednoid');
}

//Images-related Variables
$image_default 			= $params->get('image_default');
$imagefloat 			= $params->get('imagefloat', 1);
if ($params->get('show_image') != '0') {
	$imageWidth 			= intval($params->get('imageWidth', 0)) ;
	$imageHeight 			= intval($params->get('imageHeight', 0)) ;
}

//FLEXIcontent-specific Variables
$flexiwatermark			= $params->get('flexiwatermark', 1);
if ($fc) {
	$img_field_id 			= $params->get('imagefieldid');
	$date_field_id 			= $params->get('datefieldid');
	$ftags					= $params->get('flexitags');
	$ftagsjoin				= "";
	$limitag				= "";
}

//Grid layout variables
if ($params->get('grid_display') == 1) {
	$colmax 				= $params->get('colmax');
	$col 					= 0;
	$attributes				= $params->get('gridattr');
	if ((empty($colmax)) || ($colmax == 0)) {
	$colmax = 1;
	}
	$colwidth				= $params->get('colwidth');
}

//Styling Variables
$maincss				= $params->get('maincss');
$title_css				= $params->get('title_css');
$date_css				= $params->get('date_css');
$author_css				= $params->get('author_css');
$category_css			= $params->get('category_css');
$image_css				= $params->get('image_css');
$body_intro_css			= $params->get('body_intro_css');
$body_bottom_css		= $params->get('body_bottom_css');
$line_color				= $params->get('line_color');
$bottom_more_css		= $params->get('bottom_more_css');
$readmore_css			= $params->get('readmore_css');
if ($fc) {
	$flexi_fields_css		= $params->get('flexi_fields_css');
	$fields_box_css			= $params->get('fields_box_css');
}
if ($params->get('disp_catblock')) {
	$cattitle_css			= $params->get('cattitle_css');
	$catimage_css			= $params->get('catimage_css');
	$catdesc_css			= $params->get('catdesc_css');
	$catblock_css			= $params->get('catblock_css');
}
	
//FLEXIfields variables
	$field_1_id = $params->get('field_1_id');
	$field_2_id = $params->get('field_2_id');
	$field_3_id = $params->get('field_3_id');
	$field_4_id = $params->get('field_4_id');
	$field_5_id = $params->get('field_5_id');
	$field_6_id = $params->get('field_6_id');

//Categories
if ($fc) {
	$catid			= $params->get('categories');
	$catid			= is_array($catid) ? implode(',', $catid) : $catid;
	$excatid		= $params->get('excategories');
	if (is_array($excatid)) {
		$excatid	= implode(",", array_values($excatid));
	}
}else{
	$catid			= $params->get('catid');
	if (is_array($catid)) {
		$catid 		= implode(",", $catid);
	}
	$excatid 		= $params->get('excatid');
	if (is_array($excatid)) {
		$excatid	= implode(",", array_values($excatid));
	}
	$secid 			= trim( $params->get('secid'));
}

//Unordered Variables (I soon got bored XD)
$count 					= intval( $params->get('count',5));
$item_id				= intval( $params->get('item_id'));
// Test on itemid to make sure not to pass anything
	if (preg_match ('/([0-9]{1,5})/',$item_id, $result)) {
		$item_id = $result[0];
	} else {
		$item_id = '';
	}
$order 					= $params->get('order', 0);
$show_front				= $params->get('show_front', 1);
$number		 			= intval( $params->get('number', 10));
$recent 				= $params->get('recent', 0);
$limittitle				= $params->get('limittitle');
$more					= $params->get('show_more', 1);
$morelink				= $params->get('more_link');
$morewhat				= $params->get('more_what');
$username 				= $params->get('what_username', 1);
$startfrom 				= $params->get('startfrom');
$commentstable			= $params->get('commentstable', 0);
$profilesystem			= $params->get('profilesystem', 0);
$authlimit				= $params->get('limitwrittenby', 0);
$date 					=& JFactory::getDate();
$now  					= $date->toMySQL();
$dtoutput				= $params->get('dateoutput');
if (empty($dtoutput)) {
	$dtoutput = "%d %B %Y, %H.%M";
}
$nullDate 				= $db->getNullDate();
$intro					= "";
$allow					= $params->get('allow');

//Layout variables

$top1 = $params->get('display_top_1');
$top2 = $params->get('display_top_2');
$top3 = $params->get('display_top_3');
$top4 = $params->get('display_top_4');
$bottom = $params->get('display_bottom');
$flexi = $params->get('display_flexi');

//Set these to nul to avoid errors

$dtitle 	= "";
$ddate 		= "";
$dauthor	= "";
$dcat 		= "";
$dcomm 		= "";
$dhits 		= "";
$drating 	= "";
$daddcomm 	= "";
$drm	 	= "";
$dclear 	= '<div style="clear:both;"></div>';
$dempty 	= "";
$dimage		= "";

$df1		= "";
$df2		= "";
$df3		= "";
$df4		= "";
$df5		= "";
$df6		= "";

$condition_avenir = "";
$flexidateordering = "";
$ac = "";

// ---------------------------- Understand what you need to get ----------------------------

$getthis = $top1 . ' ' . $top2 . ' ' . $top3 . ' ' . $top4 . ' ' . $bottom . '' . $flexi;
$checktitle 	= strrpos ($getthis, '[title]');
$checkdate 		= strrpos ($getthis, '[date]');
$checkauthor 	= strrpos ($getthis, '[author]');
$checkcategory	= strrpos ($getthis, '[category]');
$checkhits		= strrpos ($getthis, '[hits]');
$checkcomments	= strrpos ($getthis, '[comments]');
$checkrating	= strrpos ($getthis, '[rating]');
$checkimage		= strrpos ($getthis, '[image]');
$checkaddcomm	= strrpos ($getthis, '[addcomments]');
$checkf1		= strrpos ($getthis, '[flexif1]');
$checkf2		= strrpos ($getthis, '[flexif2]');
$checkf3		= strrpos ($getthis, '[flexif3]');
$checkf4		= strrpos ($getthis, '[flexif4]');
$checkf5		= strrpos ($getthis, '[flexif5]');
$checkf6		= strrpos ($getthis, '[flexif6]');

// ---------------------------- Start gathering infos and preparing output ----------------------------

//Start from Xth article preparation
	if (empty($startfrom)) {
		$startfrom = 0;
	}
	$count += $startfrom;
	$starter = 0;
	
//Limit Author preparation

if ($authlimit != 0) {
	if ($authlimit == 1) {
		if ($my->id != 0) {
			$limitauth = "\n AND a.created_by = " . $my->id;
		}
	}elseif ($authlimit == 2) {
		if ($my->id != 0) {
			$limitauth = "\n AND a.created_by <> " . $my->id;
		}
	}elseif ($authlimit == 3) {
		if ($my->id != 0) {
			$query = 'SELECT memberid FROM #__comprofiler_members WHERE referenceid = ' . $my->id;
						$db->setQuery($query);
						$friends = $db->loadObjectList();
			if ($friends) {
				$limitauth = "\n AND (";
				$authcheck = 0;
				foreach ($friends as $friend) {
					if ($authcheck == 0) {
						$limitauth .= " a.created_by = " . $friend->memberid;
						$authcheck ++;
					}else{
						$limitauth .= " OR a.created_by = " . $friend->memberid;
					}
				}
				$limitauth .= " )";
			}
		}
	}elseif ($authlimit == 4) {
		if ($my->id != 0) {
			$query = 'SELECT connect_to FROM #__community_connection WHERE connect_from = ' . $my->id;
						$db->setQuery($query);
						$friends = $db->loadObjectList();
			if ($friends) {
				$limitauth = "\n AND (";
				$authcheck = 0;
				foreach ($friends as $friend) {
					if ($authcheck == 0) {
						$limitauth .= " a.created_by = " . $friend->memberid;
						$authcheck ++;
					}else{
						$limitauth .= " OR a.created_by = " . $friend->memberid;
					}
				}
				$limitauth .= " )";
			}
		}
	}elseif ($authlimit == 5) {
		$authors = $params->get('authors');
		if (is_array($authors)) {
			$authors = implode(",", $authors);
		}
		$limitauth = " AND a.created_by IN ( $authors )";
	}
}else{
	$limitauth = "";
}

//Limit by FLEXItags

if ($fc && $ftags) {
	if (is_array($ftags)) {
		$ftags = implode(",", $ftags);
	}
	$limitag = " AND tag.tid IN ( $ftags )";
	$ftagsjoin = "\n LEFT JOIN #__flexicontent_tags_item_relations AS tag ON tag.itemid = a.id";
}
	
//Related Items preparation
$relatedcond = '';
if (($params->get('related') == 1) || ($params->get('flexirelated') == 1)) {
	if ($id) {
		$query = 'SELECT metakey' .
			' FROM #__content' .
			' WHERE id = '.(int) $id;
			$db->setQuery($query);
			$metakey = trim($db->loadResult());

			if ($metakey) {
				// explode the meta keys on a comma
				$keys = explode(',', $metakey);
				$likes = array ();

				// assemble any non-blank word(s)
				foreach ($keys as $key) {
					$key = trim($key);
					if ($key) {
						$likes[] = ',' . $db->getEscaped($key) . ','; // surround with commas so first and last items have surrounding commas
					}
					$glue = "%' OR CONCAT(',', REPLACE(a.metakey,', ',','),',') LIKE '%";
					$relatedcond = "\n AND ( CONCAT(',', REPLACE(a.metakey,', ',','),',') LIKE '%" . implode( $glue , $likes) . "%' )";
				}
				$relnorepeat = "\n AND a.id <> " . $id;
				$reljoin = "";
				
				if (empty($relatedcond) && empty($relnorepeat)) {
					$relatedcond = "";
					$relnorepeat = "";
					$reljoin = "";
				}
			}else{
				$relatedcond = "\n AND a.id = 'die'";
				$relnorepeat = "";
				$reljoin = "";
			}
	}else{
		if ($params->get('uselangfile') == 1) {
			echo JText::_('F_RELATEDINTRO');
		}else{
			echo $relatednoid;
		}
		$relatedcond = "\n AND a.id = 'die'";
		$relnorepeat = "";
		$reljoin = "";
	}
}else{
	$relatedcond = "";
	$relnorepeat = "";
	$reljoin = "";
}

//FLEXI Related Items preparation
if ($params->get('flexirelated') == 2) {
	if ($id) {
			$reljoin = "\n LEFT JOIN #__flexicontent_tags_item_relations AS tag ON tag.itemid = a.id";
			$relcheck = 0;
			$query = 'SELECT tid' .
			' FROM #__flexicontent_tags_item_relations' .
			' WHERE itemid = '.(int) $id;
			$db->setQuery($query);
			$tags = $db->loadObjectList();
			$relatedcond = "";
			foreach ($tags as $tag) {
				if ($relcheck == 0) {
					$relatedcond .= "\n AND ( tag.tid IN ( " . $tag->tid;
					$relcheck++;
				}else{
					$relatedcond .= " ) OR tag.tid IN ( " . $tag->tid;
				}
			}
			if ($tags) {
				$relatedcond .= ") )";
			}
			if (empty($tags)) {
				if ($params->get('uselangfile') == 1) {
					echo JText::_('F_NOTHINGTOSHOW');
				}else{
					echo $nothingtoshow;
				}
				$relatedcond = "\n AND a.id = 'die'";
			}
			$relnorepeat = "\n AND a.id <> " . $id;
			
	}elseif (empty($id)){
		if ($params->get('uselangfile') == 1) {
			echo JText::_('F_RELATEDINTRO');
		}else{
			echo $relatednoid;
		}
		$relatedcond = "\n AND a.id = 'die'";
		$relnorepeat = "";
		$reljoin = "";
	}
}

//Category Title, Description and Image

if ($params->get('disp_catblock')) {
	$catcat = $params->get('catcat');
	if ($params->get('disp_cattit')) {
		$query = 'SELECT title FROM #__categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$cattit = $db->loadResult();
	}
	if ($params->get('disp_catdesc')) {
		$query = 'SELECT description FROM #__categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$catdesc = $db->loadResult();
	}
	if ($params->get('disp_cattit')) {
		$query = 'SELECT image FROM #__categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$cathimg = $db->loadResult();
	}
}

//Comments table and columns
if ($commentstable == '1') {
	$ctable = '#__jcomments';
	$cartcol = 'object_id';
}elseif ($commentstable == '2') {
	$ctable = $params->get('customtable');
	$cartcol = $params->get('customartcol');
}elseif ($commentstable == '3') {
	$ctable = '#__webeeComment_Comment';
	$cartcol = 'articleId';
}elseif ($commentstable == '4') {
	$ctable = '#__comment';
	$cartcol = 'contentid';
}elseif ($commentstable == '5') {
	$ctable = '#__yvcomment';
	$cartcol = 'parentid';
}elseif ($commentstable == '6') {
	$ctable = '#__zimbcomment_comment';
	$cartcol = 'articleId';
}elseif ($commentstable == '7') {
	$ctable = '#__rdbs_comment_comments';
	$cartcol = 'refid';
}elseif ($commentstable == '8') {
	$ctable = '#__comments';
	$cartcol = 'cotid';
}elseif ($commentstable == '9') {
	$ctable = '#__jomcomment';
	$cartcol = 'contentid';
}

//Set the variable for ordering by most commented, even if it isn't used

$ordcomments = " ";

//Ordering conditions

	if ($order == '0') {
		$ordering = " a.created DESC";
	}elseif ($order == '1'){
		$ordering = " a.hits DESC";
	}elseif ($order == '2') {
		$ordering = " RAND()";
	}elseif ($order == '3') {
	    $ordering = " a.publish_down ASC";
	    $condition_avenir = "\n AND a.publish_down >= '$now' " ;
	}elseif ($order == '4'){
		$ordering = " a.title ASC";
	}elseif ($order == '5'){
		$ordering = " a.title DESC";
	}elseif ($order == '6'){
		$ordering = " a.modified DESC, a.created DESC";
	}elseif ($order == '7'){
		$ordering = " a.ordering ASC";
	}elseif ($order == '8'){
		$ordering = " r.rating_sum DESC";
	}elseif ($order == '9'){
		$ordering = " a.created ASC";
	}elseif ($order == '10'){
		$ordering = " a.hits ASC";
	}elseif ($order == '11'){
		$ordering = " r.rating_sum ASC";
	}elseif ($order == '12'){
		$flexidateordering = "\n LEFT JOIN #__flexicontent_fields_item_relations AS fd ON a.id = fd.item_id";
		$condition_avenir = "\n AND fd.field_id = " . $date_field_id;
		$ordering = " STR_TO_DATE(fd.value,'%Y-%m-%d %H:%M:%S') DESC";
	}elseif ($order == '13'){
		$flexidateordering = "\n LEFT JOIN #__flexicontent_fields_item_relations AS fd ON a.id = fd.item_id";
		$condition_avenir = "\n AND fd.field_id = " . $date_field_id;
		$condition_avenir .= "\n AND STR_TO_DATE(fd.value,'%Y-%m-%d %H:%M:%S') >= '$now' " ;
		$ordering = " STR_TO_DATE(fd.value,'%Y-%m-%d %H:%M:%S') ASC";
	}elseif ($order == '14'){
		if ($commentstable != '0') {
			$ordcomments = ", (SELECT COUNT(*) FROM " . $ctable . " AS ordcom WHERE ordcom." . $cartcol . " = a.id ) AS comen ";
			$ordering = " comen DESC";
		}else{
			echo JText::_('COMORDWARNING');
			$ordering = " RAND()";
		}
	}elseif ($order == '15'){
		if ($commentstable != '0') {
			$ordcomments = ", (SELECT COUNT(*) FROM " . $ctable . " AS ordcom WHERE ordcom." . $cartcol . " = a.id ) AS comen ";
			$ordering = " comen ASC";
		}else{
			echo JText::_('COMORDWARNING');
			$ordering = " RAND()";
		}
	}
	
//Limit articles to current language
if ($fc) {
	if (($fish == 1) || ($fish == 2)) {
		if ($fish == 2) {
			$lingua = $limitlang;
		}
		$fishjoin = "\n LEFT JOIN #__flexicontent_items_ext AS fish ON fish.item_id = a.id";
		$fishlimit = "\n AND fish.language LIKE '" . $lingua . "%'";
	}else{
		$fishjoin = "";
		$fishlimit = "";
	}
}else{
	$fishjoin = "";
	$fishlimit = "";
}

//Frontpage Articles

if($params->get('show_front') == 2 && $fc = 0) {
	//Frontpage Articles only
	$joinfront = "\n INNER JOIN #__content_frontpage AS front ON front.content_id = a.id";
}else{
	$joinfront = ($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS front ON front.content_id = a.id' : '');
}

// Avoid duplicated joins

if ($reljoin == $ftagsjoin) {
	$ftagsjoin = "";
}

//Content Items
	if ($fc == 1) {
		$query = "SELECT DISTINCT a.*, cc.alias AS catalias, "
		. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
		. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug '
		. $ordcomments
		. "\n FROM #__content AS a"
		. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n LEFT JOIN #__flexicontent_cats_item_relations AS flexi ON a.id = flexi.itemid"
		. "\n LEFT JOIN #__content_rating AS r ON r.content_id = a.id"
		. $flexidateordering
		. $fishjoin
		. $reljoin
		. $ftagsjoin
		. "\n WHERE ( a.state = 1 AND a.sectionid > 0 )"
		. "\n AND ( a.publish_up = '$nullDate' OR a.publish_up <= '$now' )"
		. "\n AND ( a.publish_down = '$nullDate' OR a.publish_down >= '$now' )"
		. $condition_avenir      // Jolindien addition for event with date of creation of the article = dates event
		. ( $access ? "\n AND a.access <= $my->gid AND cc.access <= $my->gid" : '' )
		. ( (($catid) || ($catid === '0')) ? "\n AND ( " : '' )	
		. ( $catid ? "( flexi.catid IN ( $catid ) )" : '' )
		. ( ($catid && $catid === '0') ? " OR " : '' )
		. ( ($catid === '0') ? "( flexi.catid = '0' )" : '' )
		. ( (($catid) || ($catid === '0')) ? " )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? "\n AND ( " : '' )
		. ( $excatid ? "\n ( flexi.catid NOT IN ( $excatid ) )" : '' )
		. ( ($excatid && $excatid === '0') ? " OR " : '' )
		. ( ($excatid === '0') ? "( flexi.catid != 0 )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? " )" : '' )
		. "\n AND cc.published = 1"
		. $fishlimit
		. $limitauth
		. $limitag
		. ( $recent ? "\n AND DATEDIFF(".$db->Quote($now).", a.created) < " . $recent : '' )
		. $relnorepeat
		. $relatedcond
		. "\n ORDER BY $ordering"
	;} elseif ($fc == 0) {
		$query = "SELECT DISTINCT a.*, a.images, cc.alias AS catalias, "
		. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
		. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug '
		. $ordcomments
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n INNER JOIN #__sections AS s ON s.id = a.sectionid"
		. "\n LEFT JOIN #__content_rating AS r ON r.content_id = a.id"
		. $flexidateordering
		. $fishjoin
		. $reljoin
		. $joinfront
		. "\n WHERE ( a.state = 1 AND a.sectionid > 0 )"
		. "\n AND ( a.publish_up = '$nullDate' OR a.publish_up <= '$now' )"
		. "\n AND ( a.publish_down = '$nullDate' OR a.publish_down >= '$now' )"
		. $condition_avenir      // Jolindien addition for event with date of creation of the article = dates event
		. ( $access ? "\n AND a.access <= $my->gid AND cc.access <= $my->gid AND s.access <= $my->gid" : '' )
		. ( (($catid) || ($catid === '0')) ? "\n AND ( " : '' )	
		. ( $catid ? "( a.catid IN ( $catid ) )" : '' )
		. ( ($catid && $catid === '0') ? " OR " : '' )
		. ( ($catid === '0') ? "( a.catid = '0' )" : '' )
		. ( (($catid) || ($catid === '0')) ? " )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? "\n AND ( " : '' )
		. ( $excatid ? "\n ( a.catid NOT IN ( $excatid ) )" : '' )
		. ( ($excatid && $excatid === '0') ? " OR " : '' )
		. ( ($excatid === '0') ? "( a.catid != 0 )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? " )" : '' )
		. ( $secid ? "\n AND ( a.sectionid IN ( $secid ) )" : '' )
		. ($show_front == '0' ? ' AND f.content_id IS NULL' : '')
		. "\n AND s.published = 1"
		. "\n AND cc.published = 1"
		. $fishlimit
		. $limitauth
		. ( $recent ? "\n AND DATEDIFF(".$db->Quote($now).", a.created) < " . $recent : '' )
		. $relnorepeat
		. $relatedcond
		. "\n ORDER BY $ordering"
	;}
	$db->setQuery( $query, 0, $count );
	$rows = $db->loadObjectList();
	
if (empty($rows)) {
	if ($params->get('related') == 0 && $params->get('flexirelated') == 0) {
		if ($params->get('uselangfile') == 1) {
			echo JText::_('F_NOTHINGTOSHOW');
		}else{
			echo $nothingtoshow;
		}
	}
}else{

//Reduce queries used by getItemid for Content Items

	$bs 	= JApplication::getBlogSectionCount();
	$bc 	= JApplication::getBlogCategoryCount();
	$gbs 	= JApplication::getGlobalBlogSectionCount();

//FLEXIcontent Watermark

	if ($flexiwatermark == 0) {
		$flexipath = 'components/com_flexicontent/uploads/';
	} elseif ($flexiwatermark == 1) {
		$flexipath = 'images/stories/flexicontent/s_';
	}elseif ($flexiwatermark == 2) {
		$flexipath = 'images/stories/flexicontent/m_';
	}elseif ($flexiwatermark == 3) {
		$flexipath = 'images/stories/flexicontent/l_';
	}elseif ($flexiwatermark == 4) {
		$flexipath = $params->get('FLEXIcustom');
	}
	
//Profile Link preparation

if ($profilesystem != 0) {
	if ($profilesystem == 1) {
		$profilelink = 'index.php?option=com_comprofiler&task=userProfile&user=';
	} elseif ($profilesystem == 2) {
		$profilelink = 'index.php?option=com_community&view=profile&userid=';
	} elseif ($profilesystem == 3) {
		$profilelink = 'index.php?option=com_jsocialsuite&amp;task=profile.view&amp;id=';
	}
}else{
	$profilelink = "";
}
	
//Images alternate floating preparation
	if ($params->get('show_image') != '0') {
		$dunno = 1;
	}
	
//divs check
	$divcheck = 0;

// ---------------------------- OUTPUT ----------------------------

//Module Class SFX
	echo '<div class="aidanews' . $params->get('moduleclass_sfx') . '">';

//Show category title, image and description
if ($params->get('disp_catblock')) {
	if ($params->get('disp_catimg')) {
		if ($params->get('catimagewidth') > 0) {
			$catwidth = ' width="'.$params->get('catimagewidth').'px"';
		}else{
			$catwidth = '';
		}
		if ($params->get('catimageheight') > 0) {
			$catheight = ' height="'.$params->get('catimageheight').'px"';
		}else{
			$catheight = '';
		}
	}
	echo '<div style="' . $catblock_css . '">';
	if ($params->get('disp_cattit')) {
		echo '<div style="' . $cattitle_css . '">' . $cattit . '</div>';
	}
	if ($params->get('disp_catimg')) {
		echo '<span style="float: left; ' . $catimage_css . '"><img src="'.$Config_live_site . 'images/stories/' . $cathimg.'" title="' . $cattit . '" alt="' . $cattit . '"' . $catwidth . $catheight . '/></span>';
	}
	if ($params->get('disp_catdesc')) {
	echo '<span style="' . $catdesc_css . '">' . $catdesc . '</span>';
	}
	if ($params->get('disp_catline') == '1') {
		echo '<div style="clear:both; height: 2px; width: 100%; border-bottom: 1px solid ' . $line_color . '"></div>';
	}
	echo '</div>';
}

//Articles
foreach ( $rows as $row ) {

//Itemid
if ($item_id) {	$Itemid = $item_id;	}else{ $Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );}

// ---------------------------- IMAGES HANDLING ----------------------------

//Check if no image has to be displayed
if ($params->get('show_image') != '0') {

// ---------------------------- GET IMAGES ----------------------------

//FLEXIcontent Image Field - recoded in v 2.5 --------- $flexiimg
if (($params->get('show_image') == '3') || ($params->get('show_image') == '4') || ($params->get('show_image') == '7') || ($params->get('show_image') == '8')) {
	$query = 'SELECT value FROM #__flexicontent_fields_item_relations WHERE item_id = ' . $row->id . ' AND field_id = ' . $img_field_id;
					$db->setQuery($query);
					$flexiimg4 = $db->loadResult();
					if ($flexiimg4) {
						$flexiimg3 = strstr($flexiimg4, ';');
						$flexiimg2 = strstr($flexiimg3, '"');
						$flexiimg1 = substr($flexiimg2, 1);
						list($flexiimg0)  = explode('"', $flexiimg1);
						$flexiimg = $flexipath . $flexiimg0;
					}else{
						$flexiimg = '';
					}
}

//Get category's image -------------------------------- $catimg
if (($params->get('show_image') == '5') || ($params->get('show_image') == '6') || ($params->get('show_image') == '7') || ($params->get('show_image') == '8')) {
	$query = 'SELECT image FROM #__categories WHERE id = ' . $row->catid;
					$db->setQuery($query);
					$catimg = $db->loadResult();
					if ($catimg) { $catimg = 'images/stories/' . $catimg; }
}

//Get Community Builder Avatar ------------------------ $cbavatar
if ($params->get('show_image') == '9') {
	$query = 'SELECT avatar FROM #__comprofiler WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$cbavatar = $db->loadResult();
					if ($cbavatar) { $cbavatar = 'images/comprofiler/' . $cbavatar; }
}

//Get JomSocial Avatar -------------------------------- $jsavatar
if ($params->get('show_image') == '10') {
	if($params->get('js_avatar') == 0) {
		$query = 'SELECT avatar FROM #__community_users WHERE userid = ' . $row->created_by;
					$db->setQuery($query);
					$jsavatar = $db->loadResult();
	}elseif ($params->get('js_avatar') == 1) {
		$query = 'SELECT thumb FROM #__community_users WHERE userid = ' . $row->created_by;
					$db->setQuery($query);
					$jsavatar = $db->loadResult();
	}
}

//Get first of article's images (par Thor) ------------ $getimage
if (($params->get('show_image') == '1') || ($params->get('show_image') == '4') || ($params->get('show_image') == '6') || ($params->get('show_image') == '8')) {
	$wheretolook = $row->introtext . $row->fulltext;
	$getimage = getFirstImg($wheretolook);
}

//Get Youtube ID from {youtube} TAG ------------------- $vid
if ($params->get('youthumb')) {
	$wheretolook = $row->introtext . $row->fulltext;
	$pid = getYoutubeID($wheretolook);
	if ($pid) {
		$vid = "http://img.youtube.com/vi/" . $pid . "/default.jpg";
	}
}

//Get Image folder from {gallery} TAG ------------------- $gal
if ($params->get('gallery')) {
	$basegalfolder = $params->get('basfold');
	$wheretolook = $row->introtext . $row->fulltext;
	$al = getGalFolder($wheretolook);
	if ($al) {
		$fold = $basegalfolder . '/' . $al;
		$d = dir($fold) or die("Wrong path: $fold");
		while (false !== ($entry = $d->read())) {
			if($entry != '.' && $entry != '..' && !is_dir($dir.$entry)) {
				$gimages[] = $entry;
			}
		}
		$d->close();
		$gimgurl = $gimages[0];
		if (($gimgurl == "index.htm") || ($gimgurl == "index.html")) {
			$gimgurl = $gimages[1];
		}
		$gal = $fold . '/' . $gimgurl;
		$gimages = array();  
	}
}

// ---------------------------- ORDER IMAGES ----------------------------

//0 = No Image
if ($params->get('show_image') == '1') {
	//1 = First Image - Default
	if (!empty ($getimage)) {
		$image = $getimage;
	} else {
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '2') {
	//2 = Default Image
	$image = $image_default;
}elseif ($params->get('show_image') == '3') {
	//3 = FLEXIcontent Image - Default
	if (!empty ($flexiimg)) {
		$image = $flexiimg;
	}else{
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '4') {
	//4 = FLEXI - First - Default
	if (!empty ($flexiimg)) {
		$image = $flexiimg;
	}else{
		if (!empty ($getimage)) {
			$image = $getimage;
		} else {
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '5') {
	//Category's Image - Default
	if (!empty ($catimg)) {
		$image = $catimg;
	} else {
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '6') {
	//First - Category - Default
	if (!empty ($getimage)) {
		$image = $getimage;
	}else{
		if (!empty ($catimg)) {
			$image = $catimg;
		}else{
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '7') {
	//FLEXI - Category - Default
	if (!empty ($flexiimg)) {
		$image = $flexiimg;
	}else{
		if (!empty ($catimg)) {
			$image = $catimg;
		}else{
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '8') {
	//FLEXI - First - Category - Default
	if (!empty ($flexiimg)) {
		$image = $flexiimg;
	}else{
		if (!empty ($getimage)) {
			$image = $getimage;
		}else{
			if (!empty ($catimg)) {
				$image = $catimg;
			}else{
				$image = $image_default;
			}
		}
	}
}elseif ($params->get('show_image') == '9') {
	//CB Avatar - Default
	if (!empty ($cbavatar)) {
		$image = $cbavatar;
	}else{
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '10') {
	//JS Avatar - Default
	if (!empty ($jsavatar)) {
		$image = $jsavatar;
	}else{
		$image = $image_default;
	}
}

if ($params->get('gallery')) {
	//Gallery TAGs - Default (Overrides everything else if available)
	if ($al) {
		$image = $gal;
	}
}

if ($params->get('youthumb')) {
	//Youtube Thumbnails - Default (Overrides everything else if available)
	if ($pid) {
		$image = $vid;
	}
}

// ---------------------------- DISPLAY IMAGES ----------------------------

if ($params->get('use_thumbs')) {
	//Images - Thumbs
	
	//Check if thumbnails folder exists - if not, create it
	
	if (!is_dir('images/stories/mod_aidanews_thumbs/')) {
		mkdir('images/stories/mod_aidanews_thumbs/');
	}
	
	//If the module has a Thumb Suffix, get it and adjust it
	if ($params->get('thumbsuffix')) {
		$tsuff = $params->get('thumbsuffix') . '/';
		$foldercheck = 'images/stories/mod_aidanews_thumbs/' . $tsuff;
		if (!is_dir($foldercheck)) {
			mkdir($foldercheck);
		}
	}else{
		$tsuff = '';
	}

	$last = strrpos($image, "/");
	$name = substr($image, $last+1);
	$ext = strrchr($name, '.'); 
	$thumb = substr($name, 0, -strlen($ext)); 
	$newtb = "images/stories/mod_aidanews_thumbs/" . $tsuff . $thumb . ".jpg";

        // ABondarets - JoomGallery fix
	if (strstr($name,"com_joomgallery")) {
		if (preg_match("/[0-9]{1,}/", $name, $matchesj)) {
			$query = ' SELECT CONCAT( "/", g.jg_paththumbs, c.catpath, "/", j.imgthumbname ) ';
			$query = $query.' FROM #__joomgallery j, #__joomgallery_catg c, #__joomgallery_config g ';
			$query = $query.' WHERE j.catid = c.cid AND j.id ='.$matchesj[0];
			$db->setQuery($query);
			$newtb = $db->loadResult();
			if (!empty($imageWidth) or !empty($imageHeight)) {
				$newtb = $newtb.'" style="';
				if (!empty($imageWidth)) { $newtb=$newtb.'width: '.$imageWidth.'px; '; }; 
				if (!empty($imageHeight)) { $newtb=$newtb.'height: '.$imageHeight.'px'; };
			};
			$jgfile=true;
		};	
	}; 
	
	//if (file_exists($newtb)) {} else {
	if (file_exists($newtb)) {} elseif(!isset($jgfile)) {
        // end ABondarets - JoomGallery fix

		$tb = new ThumbAndCrop();
		$tb->openImg($image);
		
		if($imageHeight && empty($imageWidth)) {
			$newWidth = $tb->getRightWidth($imageHeight);
			$tb->creaThumb($newWidth, $imageHeight);
		}elseif(empty($imageHeight) && $imageWidth) {
			$newHeight = $tb->getRightHeight($imageWidth);
			$tb->creaThumb($imageWidth, $newHeight);
		}elseif($imageHeight && $imageWidth) {
			$newWidth = $tb->getRightWidth($imageHeight);
			$newHeight = $tb->getRightHeight($imageWidth);
			if ($newWidth > $imageWidth) {
				$subWidth = ($newWidth - $imageWidth) / 2;
				$tb->creaThumb($newWidth, $imageHeight);
				$tb->setThumbAsOriginal();
				$tb->cropThumb($imageWidth, $imageHeight, $subWidth, 0);
			}elseif ($newWidth == $imageWidth) {
				$tb->creaThumb($imageWidth, $imageHeight);
			}elseif ($newWidth < $imageWidth) {
				$subHeight = ($newHeight - $imageHeight) / 2;
				$tb->creaThumb($imageWidth, $newHeight);
				$tb->setThumbAsOriginal();
				$tb->cropThumb($imageWidth, $imageHeight, 0, $subHeight);
			}
		}else{
			$orHeight = $tb->getHeight();
			$orWidth = $tb->getWidth();
			$tb->creaThumb($orWidth, $orHeight);
		}
		
		$tb->saveThumb($newtb, $params->get('quality'));
		$tb->closeImg();
	}
	if ($params->get('use_tooltips')) {
		$image_url = '<img src="'. $newtb .'" alt="'.$row->title.'" border="0"'.'/>';
	}else{
		$image_url = '<img src="'. $newtb .'" alt="'.$row->title.'" title="'.$row->title.'" border="0"'.'/>';
	}
	
}else{
	//Images - HTML Resize
	//Set up the width and height variables
		if ($imageWidth > 0) {
			$width = ' width="'.$imageWidth.'px"';
		}else{
			$width = '';
		}
		if ($imageHeight > 0) {
			$height = ' height="'.$imageHeight.'px"';
		}else{
			$height = '';
		}
	if ($params->get('use_tooltips')) {	
		$image_url = '<img src="'.$image.'" alt="'.$row->title.'" border="0"'.$width.$height.'/>';
	}else{
		$image_url = '<img src="'.$image.'" alt="'.$row->title.'" title="'.$row->title.'" border="0"'.$width.$height.'/>';
	}
}

}

// Show Introduction
if (($params->get('show_intro') == 1) || ($params->get('use_tooltips') == 2)) {
	if ($params->get('fulltext') == 1 ) {
		$intro = strip_tags (str_replace ("<br/>"," ",$row->fulltext), $allow);
	}else{
		$intro = strip_tags (str_replace ("<br/>"," ",$row->introtext), $allow);
	}
	if ($params->get('stripplugs') == 1 ) {
		$intro = preg_replace("'{youtube}([^<]*){/youtube}'si", '', $intro);
		$intro = preg_replace("'{gallery}([^<]*){/gallery}'si", '', $intro);
		$intro = preg_replace('#\{.*?\}#', '', $intro);
	}
	if ($params->get('startfromp') == 1 ) {
		$intro = strstr($intro, '<p>');	
	}
	$intro = text_adapt($intro,$number,$params->get('shorten'),$params->get('intro_ending'));
}

//Blank itemid checker for SEF
	if ($Itemid == NULL) {
		$Itemid = '';
	} else {
		$Itemid = '&Itemid='. $Itemid;
	}
	
//Get title
$query = 'SELECT title FROM #__content WHERE id = ' . $row->id;
					$db->setQuery($query);
					$titolo = $db->loadResult();
					
//Building links
if ($params->get('URLtype') == '1') {
	$cattegory = $row->catid . "-" . $row->catalias;
	$allias = $row->id . "-" . $row->alias;
	$link = JRoute::_(ContentHelperRoute::getArticleRoute($allias, $cattegory));
}elseif ($params->get('URLtype') == '2') {
	$link = JRoute::_(FlexicontentHelperRoute::getItemRoute($row->id, $row->catid, $row->sectionid));
}elseif ($params->get('URLtype') == '3') {
	if(!defined('MY_COM_PATH')) { require_once( JPATH_ROOT . DS . 'components' . DS . 'com_myblog' . DS . 'defines.myblog.php' ); }
	require_once( MY_COM_PATH . DS . 'functions.myblog.php' );
	$row->permalinkURL	= myGetPermalinkUrl($row->id);
	$link		= $row->permalinkURL;
}else{
	$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->id, $row->catid, $row->sectionid));
}

//Get rating
if ($checkrating !== false) {
	$query = 'SELECT rating_sum FROM #__content_rating WHERE content_id = ' . $row->id;
					$db->setQuery($query);
					$voti = $db->loadResult();	
	if ($params->get('show_rating_average') == '1') {
	$query = 'SELECT rating_count FROM #__content_rating WHERE content_id = ' . $row->id;
					$db->setQuery($query);
					$media = $db->loadResult();
		if (empty($media)) {
		//If $media is 0, $voti has to be 0, so nothing happens.
		}else{
			$voti /= $media;
		}
		if ($params->get('roundrating') == 0) {
			$voti = round($voti);
		}elseif ($params->get('roundrating') == 1) {
			$voti = round($voti, 1);
		}elseif ($params->get('roundrating') == 2) {
			$voti = round($voti, 2);
		}
	}
	if (empty($voti)) {
		$voti = 0;
	}
}
//Get author name or username
if ($checkauthor !== false) {
	if ($username == 0) {
		$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
		$ac = 0;
	}elseif ($username == 1) {
		$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
		$ac = 0;
	}elseif ($username == 2) {
		if ($row->created_by_alias) {
			$author = $row->created_by_alias;
			$ac = 1;
		}else{
			$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
			$ac = 0;
		}
	}elseif ($username == 3) {
		if ($row->created_by_alias) {
			$author = $row->created_by_alias;
			$ac = 1;
		}else{
			$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
			$ac = 0;
		}
	}
}
//Get Date
if ($checkdate !== false && $params->get('flexi_date') != '1') {
	if ($params->get('what_date') == 0) {
		$row->created = JHTML::_('date', $row->created, $dtoutput);
		$date = $row->created;
	} elseif ($params->get('what_date') == 1) {
		$row->modified = JHTML::_('date', $row->modified, $dtoutput);
		$date = $row->modified;
	} elseif ($params->get('what_date') == 2) {
		$row->publish_up = JHTML::_('date', $row->publish_up, $dtoutput);
		$date = $row->publish_up;
	} elseif ($params->get('what_date') == 3) {
		$row->publish_down = JHTML::_('date', $row->publish_down, $dtoutput);
		$date = $row->publish_down;
	}
}

//Get Date from FLEXIcontent
if ($checkdate !== false && $params->get('flexi_date') == '1') {
      $query = 'SELECT value FROM #__flexicontent_fields_item_relations WHERE item_id = ' . $row->id . ' AND field_id = ' . $date_field_id ;
                  $db->setQuery($query);
                  $date = $db->loadResult();
                  $date = JHTML::_('date', $date, $dtoutput);
}

//Shorten title
if ($checktitle !== false) {
	if ( $limittitle && strlen( $row->title ) > $limittitle ) {
			   $row->title = substr( $row->title, 0, $limittitle ). $params->get('title_ending');
	}				
}

//Get Item Category
if ($checkcategory !== false) {
	$query = 'SELECT title FROM #__categories WHERE id = ' . $row->catid;
						$db->setQuery($query);
						$showcat = $db->loadResult();
}

//Floating image?

if ($params->get('show_image') != '0') {
	if ($imagefloat == 0) {
		$imgfloat = "right";
	}elseif ($imagefloat == 1) {
		$imgfloat = "left";
	}elseif ($imagefloat == 2) {
		$imgfloat = "none";
	}elseif ($imagefloat == 3) {
		if (($dunno%2)==0) {
			$imgfloat = "left";
		} else {
			$imgfloat = "right";
		}
		$dunno++;
	}elseif ($imagefloat == 4) {
		if (($dunno%2)==0) {
			$imgfloat = "right";
		} else {
			$imgfloat = "left";
		}
		$dunno++;
	}
}

//Get number of comments
if ($commentstable != '0') {
$query = 'SELECT COUNT(*) FROM ' . $ctable . ' WHERE ' . $cartcol . ' = ' . $row->id ;
						$db->setQuery($query);
						$commenti = $db->loadResult();


	if (empty($commenti)) {
		$commenti = '0';
	}
}

//Singular or plural?

if ($commentstable != '0') {
	if ($commenti == 1) {
		$commenttitle = $commenttitle_S;
	}else{
		$commenttitle = $commenttitle_P;
	}
}

if ($checkrating !== false) {
	if ($voti == 1) {
		$ratingtitle = $ratingtitle_S;
	}else{
		$ratingtitle = $ratingtitle_P;
	}
}

if ($checkhits !== false) {
	if ($row->hits == 1) {
		$hittitle = $hittitle_S;
	}else{
		$hittitle = $hittitle_P;
	}
}
	
//Get Custom Fields

if ($checkf1 !== false) {
	$flexifield1 = FLEXIfield($row->id, $field_1_id, $db);
	$ft1 = FieldType ($db, $field_1_id);
}
if ($checkf2 !== false) {
	$flexifield2 = FLEXIfield($row->id, $field_2_id, $db);
	$ft2 = FieldType ($db, $field_2_id);
}
if ($checkf3 !== false) {
	$flexifield3 = FLEXIfield($row->id, $field_3_id, $db);
	$ft3 = FieldType ($db, $field_3_id);
}
if ($checkf4 !== false) {
	$flexifield4 = FLEXIfield($row->id, $field_4_id, $db);
	$ft4 = FieldType ($db, $field_4_id);
}
if ($checkf5 !== false) {
	$flexifield5 = FLEXIfield($row->id, $field_5_id, $db);
	$ft5 = FieldType ($db, $field_5_id);
}
if ($checkf6 !== false) {
	$flexifield6 = FLEXIfield($row->id, $field_6_id, $db);
	$ft6 = FieldType ($db, $field_6_id);
}
	
//Start from Xth article

if ($starter >= $startfrom) {	

// ---------------------------- Actual Output ----------------------------

if ($params->get('grid_display') == 1) {
	if ($col == 0) {
		echo '<table style="' . $attributes . '">';
		$col++;
	}
	if ($col == 1) {
		echo '<tr>';
	}
	if ($params->get('grid_valign')) {
		if ($colwidth) {
			echo '<td width="' . $colwidth . '" style="vertical-align: top;">';
		}else{
			echo '<td style="vertical-align: top;">';
		}
	}else{
		if ($colwidth) {
			echo '<td width="' . $colwidth . '" style="vertical-align: bottom;">';
		}else{
			echo '<td style="vertical-align: bottom;">';
		}
	}
}

?>

<div style="<?php if ($params->get('clearboth') == 1) { echo 'clear: both; ';} ?><?php echo $maincss;?>">

<?php 
	if ($checktitle !== false) { $dtitle = OutputTitle($title_css, $link, $titolo, $row->title, $params->get('artblank'), $params->get('linktitle')); }
	if ($checkdate !== false) { $ddate = OutputDate($params->get('uselangfile'), $dateprefix, $date, $date_css); }
	if ($checkauthor !== false) { $dauthor = OutputAuthor($params->get('uselangfile'), $authorprefix, $profilesystem, $profilelink, $row->created_by, $author, $author_css, $ac); }
	if ($checkcategory !== false) { $dcat = OutputCategory($params->get('uselangfile'), $catprefix, $showcat, $category_css); }
	if ($params->get('commentstable') != '0') { $dcomm = OutputComments($params->get('uselangfile'), $commentprefix, $commenti, $commenttitle, $params->get('show_comment_image'), $body_bottom_css); }
	if ($checkhits !== false) { $dhits = OutputHits($params->get('show_hits_image'), $params->get('uselangfile'), $hitprefix, $row->hits, $hittitle, $body_bottom_css); }
	if ($checkrating !== false) { $drating = OutputRating($params->get('show_rating_image'), $params->get('uselangfile'), $ratingprefix, $voti, $ratingtitle, $body_bottom_css); }
	$drm = OutputRM($params->get('uselangfile'), $link, $params->get('continue_reading'), $params->get('readmore'), $titolo, $row->title, $readmore_css);
	if ($checkaddcomm !== false) { $daddcomm = Outputaddcomm($params->get('uselangfile'), $link, $addcomm, $readmore_css, $commentstable); }
	if ($params->get('show_image') != '0') { $dimage = OutputImage($imgfloat, $image_css, $link, $params->get('artblank'), $image_url, $params->get('use_tooltips'), $titolo, $intro); }
	if ($checkf1 !== false) { $df1 = OutputField($flexi_fields_css, $flexifield1, $ft1); }
	if ($checkf2 !== false) { $df2 = OutputField($flexi_fields_css, $flexifield2, $ft2); }
	if ($checkf3 !== false) { $df3 = OutputField($flexi_fields_css, $flexifield3, $ft3); }
	if ($checkf4 !== false) { $df4 = OutputField($flexi_fields_css, $flexifield4, $ft4); }
	if ($checkf5 !== false) { $df5 = OutputField($flexi_fields_css, $flexifield5, $ft5); }
	if ($checkf6 !== false) { $df6 = OutputField($flexi_fields_css, $flexifield6, $ft6); }
	$sharefb = '<a name="fb_share" type="button" href="http://www.facebook.com/sharer.php?u=' . $link . '&t=' . $row->title . '">' . $fbshare . '</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
	$turl = get_tiny_url($link);
	$tweetthis = '<a target="_blank" href="http://twitter.com/home?status=' . $titolo . ' (' . $turl . ')"><img src="modules/mod_aidanews/twitter.gif"/></a>';
	
//No default image
if ($params->get('hide_default_image') && $image == $image_default) {
	$dimage = "";
}
	
$patterns = array ('/\[title\]/', '/\[date\]/', '/\[author\]/', '/\[category\]/', '/\[comments\]/', '/\[hits\]/', '/\[rating\]/', '/\[readmore\]/', '/\[image\]/', '/\[clear\]/', '/\[empty\]/', '/\[addcomments\]/', '/\[flexif1\]/', '/\[flexif2\]/', '/\[flexif3\]/', '/\[flexif4\]/', '/\[flexif5\]/', '/\[flexif6\]/', '/\[fb\]/', '/\[tweet\]/', '/\[id\]/');
$replace = array ($dtitle, $ddate, $dauthor, $dcat, $dcomm, $dhits, $drating, $drm, $dimage, $dclear, $dempty, $daddcomm, $df1, $df2, $df3, $df4, $df5, $df6, $sharefb, $tweetthis, $row->id);

if ($top1) {
	if ($divcheck == 0) {
		$top1 = '<div> ' . $top1 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top1);
}

if ($top2) {
	if ($divcheck == 0) {
		$top2 = '<div> ' . $top2 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top2);
}

if ($top3) {
	if ($divcheck == 0) {
		$top3 = '<div> ' . $top3 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top3);
}

if ($top4) {
	if ($divcheck == 0) {
		$top4 = '<div> ' . $top4 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top4);
}

	if ($checkimage === false && $params->get('show_image') != 0) { echo $dimage; }
	if ($params->get('show_intro') == '1'):?><div style="<?php echo $body_intro_css; ?>"><?php echo $intro; if ($params->get('readmore_introtext')) { echo $drm; }?></div><?php endif;
	
if ($bottom) {
	if ($divcheck == 0) {
		$bottom = '<div> ' . $bottom . ' </div>';
	}
	echo preg_replace($patterns, $replace, $bottom);
}

if ($fc && $flexi) {
	if ($params->get('gounder') == '1') {
		$under = "clear: both;";
	}else{
		$under = "";
	}
	if ($divcheck == 0) {
		$flexi = '<div style="' . $fields_box_css . ' ' . $under . '">' . $flexi . '</div>';
	}
	echo preg_replace($patterns, $replace, $flexi);
}?>
</div>
<?php if ($params->get('show_line') == '1') : ?><div style="clear:both; height: 2px; width: 100%; border-bottom: 1px solid <?php echo $line_color; ?>"></div><?php endif; ?>

<?php
if ($divcheck == 0) {
	$divcheck++;
}

if ($params->get('grid_display') == 1) {
	echo '</td>';
	if ($col < $colmax) {
	$col++;
	}elseif (($col == $colmax) || ($col > $colmax)) {
	echo '</td></tr>';
	$col = 1;
	}
}

}elseif ($starter < $startfrom) {
	$starter++;
}
}
if ($params->get('grid_display') == 1) {
echo '</tr></table>';
}
if ($more == 1 && $params->get('related') == 0 && $params->get('flexirelated') == 0) : ?>
<div style="<?php echo $bottom_more_css; ?>"><a href="<?php echo $morelink; ?>" <?php if ($params->get('moreblank') == 1) {echo 'target="_blank"';} ?> ><?php 
if ($params->get('uselangfile') == 1) {
	echo JText::_('F_MOREARTICLES');
}else{
	echo $morewhat; 
}?></a></div>
<?php endif;?>
</div>
<?php } ?>