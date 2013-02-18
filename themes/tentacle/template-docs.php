<?
/*
Name: Docs
URI: http://tcms.me/
Description: This is the Tentacle default theme.
Author: Tentacle
Version: 1.0
License: GNU General Public License
License URI: license.txt
*/

theme::part('partials/header',array('title'=>$post->title, 'assets'=>'docs', 'prettify'=>true)); ?>

<div class="row">

    <div class="span12">

            <?= the_content( $post->content ); ?>

    </div><!-- /span12-->

</div><!-- /row-->

<? theme::part('partials/footer'); ?>