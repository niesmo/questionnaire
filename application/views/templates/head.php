<?php
$assets = $this->config->item('assets');
if(!isset($title) || $title == ""){
    $title = "iUsur";
}?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$title?> -- iUSuR</title>

	<!-- Bootstrap -->
	<link href="<?=$assets?>/plugin/bootstrap/css/themes/yeti.min.css" rel="stylesheet">
    <link href="<?=$assets?>/plugin/bootstrap/css/font-awesome.css" rel="stylesheet">
    <link href="<?=$assets?>/plugin/bootstrap/css/bootstrap-social.css" rel="stylesheet">

    <!--jQuery UI -->
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css"/>

    <!-- popup -->
    <link href="<?=$assets?>/plugin/popup/css/popup.css" rel="stylesheet">

    <!-- color picker -->
    <link href="<?=$assets?>/plugin/colorpicker/css/jquery.colorpicker.css" rel="stylesheet">

    <link href="//fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">

    <link href="<?=$assets?>/css/style.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>