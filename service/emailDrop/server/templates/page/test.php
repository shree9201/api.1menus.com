<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="format-detection" content="telephone=no" />
		
		<style type="text/css">
			<?php 
			require_once 'common/css.css';
			//echo $css = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/emailDrop/server/templates/common/css.css');
			?>
		</style>
		</head>

<body class="droptheme <?php echo $themeColor; ?>">
<table class="body-wrap" align="top"></td>
		<td class="container" width="600" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0" style="" >
				<tr>
				<td class="header" bgcolor="<?php echo $themeColorCode; ?>" >
							<?php if(array_key_exists("logo",$content) && $content->logo!="") {?><img class="logo" src="<?php echo $content->logo;?>" title="<?php if(array_key_exists("title",$content) && $content->title!=""){echo $content->title;}?>" alt="<?php if(array_key_exists("title",$content) && $content->title!=""){echo $content->title;}?>"/><?php } ?>
																		<?php if(array_key_exists("siteName",$content) && $content->siteName!=""){?><h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;"><?php echo $content->siteName;?></h1><?php }?>
																		<?php if(array_key_exists("title",$content) && $content->title!=""){?><h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:135%;"><?php echo $content->title;?></h2><?php }?>
																		<?php if(array_key_exists("subtitle",$content) && $content->subtitle!=""){?><div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;"><?php echo $content->subtitle;?></div><?php }?>
						</td>
					</tr><tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
							<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 0px;" valign="top">
										<?php if(array_key_exists("name",$content) && $content->name!=""){?>Dear <b><?php echo $content->name;?></b>,<?php }?>
									</td>
								</tr>
								
								<tr><td class="content-block">
								
								
																					<?php 
																					if (array_key_exists("level_1",$content)){
																						if(count($content->level_1)!=0){
																							for($l=0;$l<count($content->level_1);$l++){
																								echo '<p>'.$content->level_1[$l].'</p>';
																							}
																						}
																					}
																					
																					
																					
																					$StrContent = '';
																					if (array_key_exists("paragraph",$content)){
																						$para = $content->paragraph;
																						for($p=0;$p<count($para);$p++){
																							$StrContent.='<p>'.$para[$p].'</p>';
																						}
																					}
																					if (array_key_exists("link",$content)){
																						$link = $content->link;
																						foreach ($link as $k=>$v){
																						$StrContent.='<p class="button"><a  target="_blank" href="'.$v.'" class="dropbtn" style="background-color:'.$themeColorCode.';box-sizing: border-box; font-size: 14px;text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; margin: 0; padding:10px 25px;-webkit-border-radius: 15px;-moz-border-radius: 15px;border-radius: 15px;" >'.$k.'</a></p>';
																						}
																					}
																					
																					
																					echo  $StrContent;
																					if (array_key_exists("level_2",$content)){
																						if(count($content->level_2)!=0){
																							for($l=0;$l<count($content->level_2);$l++){
																								echo '<p>'.$content->level_2[$l].'</p>';
																							}
																						}
																					}
																					if (array_key_exists("level_3",$content)){
																						if(count($content->level_3)!=0){
																						 	for($l=0;$l<count($content->level_3);$l++){
																						 		echo '<p>'.$content->level_3[$l].'</p>';
																						 	}
																						 }
																					}
																					?>
								</td></tr>
								<tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										Thank You,
										<?php 
										if (array_key_exists("siteName",$content)){
											echo '<p><a style="color:'.$themeColorCode.';" target="_blank" href="'.$content->siteLink.'">'.$content->siteName.'</a></p>';
										}
										?>
										
									</td>
								</tr></table></td>
					</tr></table><div class="footer" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
					</div></div>
		</td>
		<td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
	</tr></table></body>
</html>