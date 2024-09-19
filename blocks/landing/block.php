<?php
/**
 * Block template file: block.php
 *
 * Landing Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'landing-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'acf-block block-landing';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}

$classes .= ' mt-10';
?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
    <div class="container text-center prose">
        <h3 class="text-3xl">
            What's In My Food?
        </h3>
        <p class="text-center">
            <img src="<?php echo assets_url('/dist/images/photo.jpg'); ?>" class=" mx-auto" />
        </p>
        <p class="text-2xl mb-10 text-primary font-bold">
            Snap a photo of an ingredient list to uncover information about each ingredient.
        </p>
    </div>

    <p class="pb-[40px]">
        <img src="<?php echo assets_url('/dist/images/food.png'); ?>" class="m-0 w-full" />
    </p>

    <div class="fixed bottom-[20px] left-0 w-full">
        <div class="container">
            <a class="btn btn-primary btn-lg w-full text-2xl rounded-none" href="/camera">Try It Now</a>
        </div>
    </div>

</div>