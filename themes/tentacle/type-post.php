<?
/*
Type: Post
*/

theme::part('partials/header',array('title'=>$post->title,'assets'=>'default')); ?>

<div class="row">

    <div class="span3">

        <div class="well sidebar-nav">
            <? theme::part( 'partials/sidebar' ); ?>
        </div><!-- /well -->

    </div><!-- /span3-->

    <div class="span9">
        <div class="post <?= $post->template; ?>">
            <? $author_meta = $author->get_meta( $post->author ); ?>
            <div class="page-header">
                <h2><?= $post->title; ?></h2>
            </div>

                <p class="meta"><em>
                        Posted <time datetime="<? date::show($post->date) ?>" pubdate=""><? date::show($post->date) ?></time>
                        by <?= $author_meta->first_name;?> <?= $author_meta ->last_name;?> <span class="amp"></span>
                        in <? foreach( $category->get_relations( $post->id ) as $relation ): ?>
                            <a href="<?=BASE_URL?>category/<?=$relation->slug ?>"><?= $relation->name ?></a>
                        <? endforeach; ?>.
                </em></p>

                <?= the_content( $post->content ); ?>

                <p>
                    <small>Tags:
                        <? foreach( $relations = $tag->get_relations( $post->id ) as $relation ): ?>
                            <a href="<?=BASE_URL?>tag/<?=$relation->slug ?>" class="label"><?= $relation->name ?></a>
                        <? endforeach; ?>
                    </small>
                </p>

                <?= render_content(); ?>
            </div>

        </div><!-- /post -->
    </div><!-- /span9-->

</div><!-- /row-->

<? theme::part('partials/footer'); ?>