<?
/*
Project: AWD Framework
File: Header (blank template)
Created By Adam Duncan
Date: 04/17/2016
Desc: default header for awd to prove it works.

*/

global $AWD;
$settings = blank_load_websitesettings();
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html lang="en-US" prefix="og: http://ogp.me/ns#"> <!--<![endif]-->
<html class="no-js" dir="ltr"
  xmlns:og="http://ogp.me/ns#"
  xmlns:article="http://ogp.me/ns/article#"
  xmlns:book="http://ogp.me/ns/book#"
  xmlns:profile="http://ogp.me/ns/profile#"
  xmlns:video="http://ogp.me/ns/video#"
  xmlns:product="http://ogp.me/ns/product#"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dc="http://purl.org/dc/terms/"
  xmlns:foaf="http://xmlns.com/foaf/0.1/"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:sioc="http://rdfs.org/sioc/ns#"
  xmlns:sioct="http://rdfs.org/sioc/types#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
  xmlns:schema="http://schema.org/">
  
  <head profile="http://www.w3.org/1999/xhtml/vocab">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=0.7, user-scalable=no">
	<meta name="robots" content="follow, index" />
	<meta name="generator" content="" />
	  
	<? awd_write_metadata($settings['title'], $settings['keywords'], $settings['description']);
	 awd_csslayout_tags(true); ?>
  </head>
  <body>
	<div id="awd-blank">
		<header id="awd-header">
			<h1 class="logo"><?=$settings['title'];?></h1>
			<h2 class="site-description"><?=$settings['site-description'];?></h2>
			<nav>
				<? blank_write_menu("header"); ?>
			</nav>
		</header>
		
		<main id="awd-content" class="content <?=$AWD['page'];?>-page">