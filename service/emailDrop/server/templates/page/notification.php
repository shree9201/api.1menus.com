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
		<center >
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
				<tr>
					<td class="container" align="center" valign="top" id="bodyCell">
						
						<table class="main"    border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
							<tr>
								<td  align="center" valign="top">
									<table class="header bg" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="<?php echo $themeColorCode; ?>" >
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top" class="textContent">
																	<?php if(array_key_exists("logo",$content) && $content->logo!="") {?><img src="<?php echo $content->logo;?>" title="<?php if(array_key_exists("title",$content) && $content->title!=""){echo $content->title;}?>" alt="<?php if(array_key_exists("title",$content) && $content->title!=""){echo $content->title;}?>"/><?php } ?>
																		<?php if(array_key_exists("siteName",$content) && $content->siteName!=""){?><h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;"><?php echo $content->siteName;?></h1><?php }?>
																		<?php if(array_key_exists("title",$content) && $content->title!=""){?><h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:135%;"><?php echo $content->title;?></h2><?php }?>
																		<?php if(array_key_exists("subtitle",$content) && $content->subtitle!=""){?><div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;"><?php echo $content->subtitle;?></div><?php }?>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr mc:hideable>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="textContent">
																					<?php if(array_key_exists("name",$content) && $content->name!=""){?>Dear <b><?php echo $content->name;?></b>,<?php }?>
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
																					
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
												
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								
							</tr>
							
							<tr>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td valign="top" width="500" class="flexibleContainerCell">
															<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																<tr>
																<?php 
																$color = array('#5F5F5F','#27ae60','#5F5F5F','#27ae60','#5F5F5F','#27ae60','#5F5F5F','#27ae60','#5F5F5F','#27ae60');
																if (array_key_exists("box",$content)){
																if(count($content->box)!=0){
																	$box = $content->box;
																	$cnt=0;
																foreach ($box as $key=>$val){?>
																	<td align="left" valign="top" class="flexibleContainerBox" style="background-color:<?php echo $color[$cnt]?>;">
																		<table border="0" cellpadding="30" cellspacing="0" width="100%" style="max-width:100%;">
																			<tr>
																				<td align="left" class="textContent">
																					<h3 style="color:#FFFFFF;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;"><?php echo $key;?></h3>
																					<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;"><?php echo $val;?></div>
																				</td>
																			</tr>
																		</table>
																	</td>
																<?php $cnt++; }
																}}
																?>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
								<?php if (array_key_exists("last",$content)){?>
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
														
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td align="center" valign="top">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td valign="top" class="textContent">
																				<hr>
																					<div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;margin-top:3px;color:#5F5F5F;line-height:135%;">
																					<?php if(count($content->last)!=0){for($l=0;$l<count($content->last);$l++){echo '<p>'.$content->last[$l].'</p>';}}?>
																					</div>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<?php }?>
								</td>
							</tr>

						</table>

						<table bgcolor="#E1E1E1" border="0" cellpadding="0" cellspacing="0" width="500" id="emailFooter">
							<tr>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
													<tr>
														<td align="center" valign="top" width="500" class="flexibleContainerCell">
															<table border="0" cellpadding="30" cellspacing="0" width="100%">
																<tr>
																	<td valign="top" bgcolor="#E1E1E1">

																		<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
																			<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
																						If you can't see this message, <a href="#" target="_blank" style="text-decoration:none;border-bottom:1px solid #828282;color:#828282;"><span style="color:#828282;">view&nbsp;it&nbsp;in&nbsp;your&nbsp;browser</span></a>.
																					</div>
																		</div>

																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>

						</table>

					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
