<?php 
echo "26-May-2026 12:00:00";
require_once 'service/constant.php';
$action 	= isset($_REQUEST ['action'])?$_REQUEST ['action']:"";
if($action!=""){
	require_once 'api.php';	
}else{
?>
<!DOCTYPE html>
<head>
    <title>API Documentation for the <?php echo SITE_TITLE;?> Application</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">    
    <link rel="shortcut icon" href="favicon.ico">  
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo API_THEME_PATH;?>plugins/bootstrap/css/bootstrap.min.css">   
    <link rel="stylesheet" href="<?php echo API_THEME_PATH;?>plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo API_THEME_PATH;?>plugins/prism/prism.css">
    <link rel="stylesheet" href="<?php echo API_THEME_PATH;?>plugins/elegant_font/css/style.css">
    <link id="theme-style" rel="stylesheet" href="<?php echo API_THEME_PATH;?>css/styles.css">    
</head> 

<body class="body-green">
    <div class="page-wrapper">
        <!-- ******Header****** -->
        <header id="header" class="header">
            <div class="container">
                <div class="branding">
                    <h1 class="logo">
                        <a href="index.html">
                            <span aria-hidden="true" class="icon_documents_alt icon"></span>
                            <span class="text-highlight">API </span><span class="text-bold"> Docs</span>
                        </a>
                    </h1>
                </div><!--//branding-->
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">API Details</li>
                </ol>
            </div><!--//container-->
        </header><!--//header-->
        <div class="doc-wrapper">
            <div class="container">
                <div id="doc-header" class="doc-header text-center">
                    <h1 class="doc-title"><i class="icon fa fa-paper-plane"></i> <?php echo SITE_TITLE;?></h1>
                    <div class="meta"><i class="fa fa-clock-o"></i> 
                        <?php 
                            $filename = 'index.php';
                            if (file_exists($filename)) {
                                $latestupdated = date ("F d Y.", filemtime($filename));
                                echo "Last modified: " . $latestupdated;
                            }
                        ?>
                    </div>
                </div><!--//doc-header-->
                <div class="doc-body">
                    <div class="doc-content">
                        <div class="content-inner">
                            <?php
                            require_once 'pages/1_list.php'; 
                            $total =count($apiNamesArray);
                                for($i=0;$i<$total;$i++){
                                $sname = $keys[$i];
                                $name = ucfirst($apiNamesArray[$sname]);
                                $fileName = 'pages/'.$sname.".php";
                            ?>
                            <section id="<?php echo $keys[$i]; ?>-section" class="doc-section">
                                <h2 class="section-title"><?php echo $name; ?></h2>
                                <div class="section-block">
                                    <?php 
                                    include_once($fileName);
                                    ?>
                                </div>
                            </section>
                            <?php } ?>
                            </section><!--//doc-section-->
                        </div><!--//content-inner-->
                    </div><!--//doc-content-->
                    <div class="doc-sidebar hidden-xs">
                        <nav id="doc-nav">
                            <ul id="doc-menu" class="nav doc-menu" data-spy="affix">
                            <?php
                                $total =count($apiNamesArray);
                                for($i=0;$i<$total;$i++){
                                $sname = $keys[$i];
                                $name = ucfirst($apiNamesArray[$sname]);
                            ?>
                                <li><a class="scrollto" href="#<?php echo $sname;?>-section"><?php echo $name;?></a></li>
                            <?php } ?>
                            </ul><!--//doc-menu-->
                        </nav>
                    </div><!--//doc-sidebar-->
                </div><!--//doc-body-->              
            </div><!--//container-->
        </div><!--//doc-wrapper-->
        
    </div><!--//page-wrapper-->
    
    <footer id="footer" class="footer text-center">
        <div class="container">        
            <small class="copyright">Designed with <i class="fa fa-star"></i> by <a href="http://droptech.in/" targe="_blank">Droptech IT Solution</a> for developers</small>
            
        </div><!--//container-->
    </footer><!--//footer-->
    
     
    <!-- Main Javascript -->          
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>plugins/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>plugins/prism/prism.js"></script>    
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>                                                                
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>plugins/jquery-match-height/jquery.matchHeight-min.js"></script>
    <script type="text/javascript" src="<?php echo API_THEME_PATH;?>js/main.js"></script>
    
</body>
</html> 
<?php 
}
function APIInfoPageStart($pageName,$infoPass){	
	?>
	<div class="code-block">
<p><code><i class="fa fa-info"></i></code> <?php echo ucfirst($pageName);?> process information</p>
<pre class="language-css"><code class="language-css">
	<span class="token property">action</span>:<span class="token string"> <?php echo $pageName;?></span>
	<span class="token property">payload</span>:<span class="token string"> action=<?php echo $pageName.$infoPass['structure'];?></span>
	<span class="token property">responce</span>:<span class="token string"><?php echo $infoPass['responce'];?></span>
</code>
<div class="table-responsive doc-table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr class="color"><td colspan="2" class="text-center"><b>Details arguments list</b></td></tr>
            <tr class="color2">
                <th width="20%">Field Name</th>
                <th>Field Description</th>
            </tr>
        </thead>
     <?php 
}
function APIInfoPageEnd(){
	?></table>
</div>
</pre>
</div><?php 
}

?>
