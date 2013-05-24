<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<title><?= get::option('blogname').' - '.$title ?></title>

	<? if (isset($download) && $download == true): ?>
		<meta content="0; URL=http://api.tentaclecms.com/get/download/" http-equiv="Refresh" />
	<? endif;?>

	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<meta name="description" content="">
	<meta name="author" content="">
	<? theme::assets($assets);
      render_header( ); ?>
</head>
<? $prettify = false ;?>
<body <?= body_class(); ?> <? if ($prettify)echo 'onLoad="NDOnLoad();prettyPrint();"' ?>>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container hidden-phone hidden-tablet">
				<ul class="nav">
					<li>
						<a href="<?= BASE_URL ?>" class="brand" ><img src="<?= THEME ?>/assets/img/tentacle.png" alt=""/></a>
					</li>
				</ul>
				<ul class="nav pull-right">
					<li>
						<a href="https://twitter.com/#!/TentacleCMS" target="_blank"  onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Twitter']);  mixpanel.track('Header Navigation', { 'link': 'Twitter' });">@TentacleCMS</a>
					</li>
					<li class="">
						<a href="http://try.tentaclecms.com" target="_blank" onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Demo']);   mixpanel.track('Header Navigation', { 'link': 'Demo' });">Try it!</a>
					</li>
					<li class="">
						<a href="http://tentaclecms.com/blog/"  onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Blog']);  mixpanel.track('Header Navigation', { 'link': 'Blog' });">Blog</a>
					</li>
					<li class="">
						<a href="http://tentaclecms.com/docs/"  onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Documentation']);   mixpanel.track('Header Navigation', { 'link': 'Documentation' });">Documentation</a>
					</li>
					<li>
						<a href="http://community.tentaclecms.com/"  onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Community']);   mixpanel.track('Header Navigation', { 'link': 'Community' });">Community</a>
					</li>
					<li class="">
						<a href="mailto:hello@tentaclecms.com" onClick="_gaq.push(['_trackEvent', 'Navigation Link', 'Link', 'Contact']);   mixpanel.track('Header Navigation', { 'link': 'Contact' });">Contact Us</a>
					</li>
				</ul>
			</div>
		</div>
	</div>