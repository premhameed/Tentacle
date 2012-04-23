<?
 /*
Name: Page
URI: http://tcms.me/
Description: This is the Tentacle default theme.
Author: Tentacle
Version: 1.0
License: GNU General Public License
License URI: license.txt
*/

$scaffold_data = array();

// If SCAFFOLD is not set then display the theme content.
if( !defined( 'SCAFFOLD' ) ):?>
<? load_part('header',array('title'=>$post->title, 'assets'=>'default')); ?>

<div class="row-fluid">
	<div class="span3">
		<div class="well sidebar-nav">
			
			<? load_part( 'sidebar' ); ?>
			
		</div><!--/.well -->
	</div><!--/span3-->
	<div class="span9">
		<div class="hero-unit">
			
			<h1><?= $post->title; ?></h1>
			<?  // Strip slashses will remove any special 
				// encoding used by the data base.
				//
			 	// This will be replaced by a cuntion that
			 	// will process any Shortcodes and OEMBED data.
			?>
			<?= render_content( $post->content ); ?>
			
		</div><!-- /hero-unit -->
	</div><!--/span9-->
</div><!--/row-->

<? load_part('footer'); ?> 

<? endif; ?>