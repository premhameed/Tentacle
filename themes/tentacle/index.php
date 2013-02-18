<?
 /*
Name: Default
URI: http://tcms.me/
Description: This is the Tentacle default theme.
Author: Tentacle
Version: 1.0
License: GNU General Public License
License URI: license.txt
*/

theme::part('partials/header', array('title'=>$post->title, 'assets'=>'default')); ?>

<div class="row">

    <div class="span3">

        <div class="well sidebar-nav">
            <? theme::part( 'partials/sidebar' ); ?>
        </div><!--/.well -->

    </div><!--/span3-->

    <div class="span9">

        <div class="hero-unit">

            <h1><?= $post->title; ?></h1>

            <?= the_content( $post->content ); ?>

        </div><!-- /hero-unit -->

    </div><!-- /span9-->

</div><!-- /row-->

<? theme::part('partials/footer'); ?>