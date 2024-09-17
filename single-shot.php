<?php get_header(); ?>

<div class="container">

    <?php the_post_thumbnail('full'); ?>

    <?php 
        $chatgpt_response = get_field('chatgpt_response');
        $json = json_decode($chatgpt_response, true);

        pr($json);

    ?>
    
</div>

<?php get_footer(); ?>