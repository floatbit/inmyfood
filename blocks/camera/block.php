<?php
/**
 * Block template file: block.php
 *
 * Camera Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'camera-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'acf-block block-camera';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}
?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">
        
    <div class="video-container">
        <video id="video" autoplay playsinline></video>
        <input type="range" id="zoom-slider" min="1" max="10" step="0.1" value="1">
    </div>
    <div class="mt-4">
        <button id="capture-button">Take Photo</button>
        <button id="switch-camera">Switch Camera</button>
    </div>
    <canvas id="canvas"></canvas>
    <form id="image-form" method="POST" action="/process" enctype="multipart/form-data">
        <input type="hidden" name="image" id="image-data">
        <input type="hidden" name="date" id="current-date">
        <input type="hidden" name="time" id="current-time">
        <input type="hidden" name="day" id="current-day">
        <button id="submit-button" type="submit">Submit</button>
        <button id="retake-button" type="button">Retake</button>
    </form>

    </div>
</div>