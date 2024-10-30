<?php

// ----------------------------------------------------------------------------------------------------------------------------------------------------------
//					JOOMOOD START PLAYING
// ----------------------------------------------------------------------------------------------------------------------------------------------------------

// SHOW LAST SE X PUBLIC ALBUMS

    include(ABSPATH.'wp-content/plugins/giggi_functions/giggi_database.php');
    require_once(ABSPATH.'wp-content/plugins/giggi_functions/giggi_functions.php');


// Check some data...

if($nametype=="1" OR $nametype=="2") {
$nametypez=$nametype;
} else {
$nametypez="2";
}

		// Check for hidden description
		
		$hiddesc=strtolower($hide_desc);
		if($hiddesc=="yes") {
		$hide_desc="yes";
		} else {
		$hide_desc="no";
		}

		// Check the group description cut-off point
		
        if (!$cut_off=="") {
        $cut="1";
        } else {
        $cut="0";  // vuol dire che l'utente non ha inserito un numero!
        }

		// Check for Splitted Stats
		
		$split_stat=strtolower($split_stat);
		if($split_stat=="yes") {
		$split="1";
		} else {
		$split="0";
		}
		
		// Check if Stats are Showed
		
		$show_stat=strtolower($show_stat);
		if($show_stat=="yes") {
		$shows="1";
		} else {
		$shows="0";
		}
		
		// Check personal width & height...

        if (preg_match ("/^([0-9.,-]+)$/", $pic_dim_width)) {
        $my_w="1";
        } else {
        $my_w="0";  // vuol dire che l'utente non ha inserito un numero!
        }
        if (preg_match ("/^([0-9.,-]+)$/", $pic_dim_height)) {
        $my_h="1";
        } else {
        $my_h="0";  // vuol dire che l'utente non ha inserito un numero!
        }

        if($pic_dim_width=="0" OR $pic_dim_height=="0" OR $pic_dim_width=="" OR $pic_dim_height=="" OR $my_w=="0" OR $my_h=="0") {
        $pic_dimensions="0";
        } else {
        $pic_dimensions="1";
        }

        if($pic_dimensions =="1") {
		
		$mywidth=$pic_dim_width;
		$myheight=$pic_dim_height;
		
		} else {
		$mywidth="60";
		$myheight="60";
		
		}

		// Check Num of Groups...

		if($numOfGroup<0) {
		$numOfGroup=1;
		}

		if($how_many_groups>$numOfGroup) {
		$how_many_groups=$numOfGroup;
		}
		

		// Text Reduction...

		// Check personal width & height...

        if (preg_match ("/^([0-9.,-]+)$/", $text_redux)) {
        $my_t="1";
        } else {
        $my_t="0";  // vuol dire che l'utente non ha inserito un numero!
        }

		if($my_t=="0") {
		$redux="100";
		} else {
		$redux=$text_redux;
		}

// ---------------------------------------------------------

		// Check Main Box border style
		
		if ($mainbox_border_style=="0" OR $mainbox_border_style=="1" OR $mainbox_border_style=="2") {
		$mainbox_border_res="1";
		} else {
		$mainbox_border_res="0";
		}

		// Check Main Box border color
		
		if ($mainbox_border_color!=='') {
		$mainbox_bordercol_res="1";
		} else {
		$mainbox_bordercol_res="0";
		}

		// Substitute empty or wrong fields
		
		if ($mainbox_border_res=="0") {
		$mainboxbord="0px solid";
		} 
		
		if ($mainbox_border_style=="1") {
		$mainboxbord="{$mainbox_border_dim}px dotted";
		} 
		
		if ($mainbox_border_style=="2") {
		$mainboxbord="{$mainbox_border_dim}px solid";
		} 
		

		if ($mainbox_bordercol_res=="0") {
		$mainboxbordcol="#ffffff";
		} else {
		$mainboxbordcol=$mainbox_border_color;
		}
		
		$mainboxbgcol=$mainbox_bg_color;

		$mainbox_width=$mainbox_width."%";

// ---------------------------------------------------------

		
		// Check Inner Box border style
		
		if ($box_border_style=="0" OR $box_border_style=="1" OR $box_border_style=="2") {
		$box_border_res="1";
		} else {
		$box_border_res="0";
		}

		// Check box border color
		
		if ($box_border_color!=='') {
		$box_bordercol_res="1";
		} else {
		$box_bordercol_res="0";
		}
		
		
		// Substitute empty or wrong fields
		
		if ($box_border_res=="0") {
		$boxbord="0px solid";
		} 
		
		if ($box_border_style=="1") {
		$boxbord="{$box_border_dim}px dotted";
		} 
		
		if ($box_border_style=="2") {
		$boxbord="{$box_border_dim}px solid";
		} 
		

		if ($box_bordercol_res=="0") {
		$boxbordcol="#ffffff";
		} else {
		$boxbordcol=$box_border_color;
		}
		
		$boxbgcol=$box_bg_color;
		$boxbgcol1=$box_bg_color1;
		

		// Build Full Style Variables
		
		$mystyle="style=\"border:".$boxbord." ".$boxbordcol."; background-color: ".$boxbgcol.";\"";
		$mystyle0="style=\"border:".$boxbord." ".$boxbordcol."; background-color: ".$boxbgcol.";\"";
		$mystyle1="style=\"border:".$boxbord." ".$boxbordcol."; background-color: ".$boxbgcol1.";\"";
		$mymainstyle="style=\"border:".$mainboxbord." ".$mainboxbordcol."; background-color: ".$mainboxbgcol.";\"";
		$titlestyle="padding: 0px 2px 2px 0px; border-bottom: 1px solid #CCCCCC; margin-bottom: 2px;";
		$bodystyle="margin-bottom: 0px;";
		$statstyle="font-size: 7pt; color: #777777; font-weight: normal;";
		$smalltxt="font-size:{$redux}%;";


// ----------------------------------------------------------------------------------------------------------------------------------------------------------
//					LET'S START QUERY TO RETRIEVE OUR DATA
// ----------------------------------------------------------------------------------------------------------------------------------------------------------


$query  = "SELECT u.*, p.*, t.*, FROM_UNIXTIME((t.album_datecreated), '%d/%m/%y') as created,
FROM_UNIXTIME((t.album_dateupdated), '%H:%i') as updated, count(p.media_id) as total 
FROM se_albums t LEFT JOIN se_media p ON (p.media_album_id=t.album_id)
JOIN se_users u ON (t.album_user_id=u.user_id)
WHERE t.album_privacy='15' OR t.album_privacy='63'
GROUP by t.album_id
ORDER by t.album_datecreated DESC limit ".$numOfGroup."";

$result = mysql_query($query);


while($row = mysql_fetch_array($result, MYSQL_ASSOC))

{
	
	
if ($choose_date=="1") {
$miovalore= giggitime2($row['album_datecreated'], $num_times=1).' ago';
$miovalore1= giggitime2($row['album_dateupdated'], $num_times=1).' ago';
} else {
$miovalore= giggitime($row['album_datecreated'], $num_times=1).' ago';
$miovalore1= giggitime($row['album_dateupdated'], $num_times=1).' ago';
}

// Choose a name...

if ($nametypez=="2") {
$mynome=$row['user_displayname'];
} else {
$mynome=$row['user_username'];
}


$mydir=$wpdir."/wp-content/plugins/wp-se_albums";
$subdir = $row['user_id']+999-(($row['user_id']-1)%1000);



if($row['media_id']==NULL) {

if($use_resize !=="no") {
$mypic="<img src=\"{$mydir}/image.php/icon.gif?width={$mywidth}&amp;height={$myheight}&amp;cropratio=1:1&amp;quality=100&amp;image={$socialdir}/images/icons/folder_big.gif\" width=\"{$mywidth}\" height=\"{$myheight}\" style=\"border:".$image_border."px ".$image_bordercolor." solid\" alt=\"".$mynome."\" />";
} else {
$mypic="<img src=\"{$socialdir}/images/icons/folder_big.gif\" width=\"{$mywidth}\" height=\"{$myheight}\" style=\"border:".$image_border."px ".$image_bordercolor." solid\" alt=\"".$mynome."\" />";
}


} else {

if($use_resize !=="no") { // RESIZING SCRIPT

if ($row['album_cover']!='') {
// Creates a thumbnail based on your personal dims (width/height), without stretching the original pic
$mypic="<img src=\"{$mydir}/image.php/{$row['album_cover']}.{$row['media_ext']}?width={$mywidth}&amp;height={$myheight}&amp;cropratio=1:1&amp;quality=100&amp;image={$socialdir}/uploads_user/{$subdir}/{$row['user_id']}/{$row['album_cover']}.{$row['media_ext']}\" style=\"border:".$image_border."px solid ".$image_bordercolor."\" alt=\"".$mynome."\" />";
} else {
$mypic="<img src=\"{$mydir}/image.php/nophoto.gif?width={$mywidth}&amp;height={$myheight}&amp;cropratio=1:1&amp;quality=100&amp;image={$socialdir}/{$empty_image_url}\" style=\"border:".$image_border."px ".$image_bordercolor." solid\" alt=\"".$mynome."\" />";
}

} else { // NO RESIZING SCRIPT

if ($row['album_cover']!='') {
// Creates a thumbnail based on your personal dims (width/height)
$myp="{$row['album_cover']}_thumb.{$row['media_ext']}";
$mypfile=$socialdir."/uploads_user/{$subdir}/{$row['user_id']}/{$myp}";

if (@fopen($mypfile, "r")) {
$myps=$myp;
$mypfile1=$socialdir."/uploads_user/{$subdir}/{$row['user_id']}/{$myps}";
} else {
$myps="{$row['album_cover']}.{$row['media_ext']}";
$mypfile1=$socialdir."/uploads_user/{$subdir}/{$row['user_id']}/{$myps}";
}
$mypic="<img src=\"{$mypfile1}\" width=\"{$mywidth}\" height=\"{$myheight}\" style=\"border:".$image_border."px solid ".$image_bordercolor."\" alt=\"".$mynome."\" />";
} else {
$mypic="<img src=\"{$socialdir}/{$empty_image_url}\" width=\"{$mywidth}\" height=\"{$myheight}\" style=\"border:".$image_border."px ".$image_bordercolor." solid\" alt=\"".$mynome."\" />";
}

}
}

// Splitted or not-splitted Stats? This is the question...

// Cut a little bit the group description field...

$mydesc = $row['album_title'];

if($cut=="0" OR $cut_off=="0" OR $cut_off=="") {
$shortdesc=$mydesc;
} else {
$shortdesc = substr($mydesc,0,$cut_off)."...";
}


// Alternate Color

if ($pp%2 !==0) {
$mystyle=$mystyle1;
} else {
$mystyle=$mystyle0;
}


// Pic-Pics?

if($row['total']>1) {
$picdef="{$row['total']} pics";
} else if($row['total']==1) {
$picdef="1 pic";
} else {
$picdef="No Pic";
}



// Create a Pic-Title

if ($data_type=="1") {
$pictitle="{$shortdesc} by {$mynome}: {$picdef}, created {$miovalore}, updated {$miovalore1}";
} else {
$pictitle="{$go_profile_text1} {$shortdesc}";
}

// Create a link to the album/media/owner


// Create a link to the album

$mylink0="<a href=\"".$socialdir."/album.php?user={$row['user_username']}&album_id={$row['album_id']}\" title=\"{$pictitle}\">";

// Create a link to the pic

$mylink2="<a href=\"".$socialdir."/album_file.php?user={$mynome}&album_id={$row['album_id']}&media_id={$row['media_id']}\" title=\"{$pictitle}\">";


// Create a link to the album owner

$mylink1="<a href=\"".$socialdir."/profile.php?user_id={$row['album_user_id']}\" title=\"{$go_profile_text} {$mynome}\">";




// Show Pic and Text

if ($data_type=="3") {

if($how_many_groups=="1") {
$mywi=100;
} else {
$mywi=floor(100/$how_many_groups);
}

$myw="width=\"{$mywi}%\" ";
$textdata="
<table width=\"{$myw}\" cellspacing=\"{$inner_cellspacing}\" cellpadding=\"{$inner_cellpadding}\" ".$mystyle.">
<tr>
<td width=\"{$mywidth}\" align=\"left\" valign=\"top\">{$mylink2}{$mypic}</a></td>
<td align=\"left\" valign=\"middle\"><div style=\"{$titlestyle}\"><span style=\"{$smalltxt}\">{$shortdesc} by {$mylink1}{$mynome}</a></span></div>
<div style=\"{$statstyle}\">{$picdef}, created {$miovalore}, updated {$miovalore1}</div></td>
</tr>
</table>
</td>
";
} 

// Show Only Text

else if ($data_type=="2") {

if($how_many_groups=="1") {
$mywi=100;
} else {
$mywi=floor(100/$how_many_groups);
}

$myw="width=\"{$mywi}%\" ";
$textdata="
<table width=\"{$myw}\" cellspacing=\"{$inner_cellspacing}\" cellpadding=\"{$inner_cellpadding}\" ".$mystyle.">
<tr>
<td width=\"{$mywidth}\" align=\"left\"><div style=\"{$titlestyle}\"><span style=\"{$smalltxt}\">{$shortdesc} by {$mylink1}{$mynome}</a></span></div>
<div style=\"{$statstyle}\">{$picdef}, created {$miovalore}, updated {$miovalore1}</div></td>
</tr>
</table>
</td>
";
} 

// Show Only Pic

else {

$myw="width=\"{$mywidth}\" ";
$textdata="
{$mylink2}{$mypic}</a>
</td>
";
}


if($i<$how_many_groups) {


$rows .= "
<td align=\"left\" valign=\"top\" {$myw}>
{$textdata}";

} else {

$rows .= "
</tr><tr><td align=\"left\" valign=\"top\" {$myw}>
{$textdata}";

$i=0;
}

$i++;
$pp++;

}

if ($data_type=="1") {
$www=$mywidth*$pp;
$content .=" <table width=\"{$www}\" cellspacing=\"{$outer_cellspacing}\" cellpadding=\"{$outer_cellpadding}\" bgcolor=\"{$mainbox_bg_color}\" {$mymainstyle}><tr>";
} else {
$content .=" <table width=\"{$mainbox_width}\" cellspacing=\"{$outer_cellspacing}\" cellpadding=\"{$outer_cellpadding}\" bgcolor=\"{$mainbox_bg_color}\" {$mymainstyle}><tr>";
}
$content .="{$rows}";

$content .="</tr></table>";

echo $content;


// ----------------------------------------------------------------------------------------------------------------------------------------------------------
//					END OF JOOMOOD FUNNY TOY
// ----------------------------------------------------------------------------------------------------------------------------------------------------------

?>