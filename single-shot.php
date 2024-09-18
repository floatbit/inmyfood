<?php get_header(); ?>

<?php
    $chatgpt_response = get_field('chatgpt_response');
    $json = json_decode($chatgpt_response, true);
?>

<?php if (has_post_thumbnail()): ?>
    <div class="container mb-10">
        <?php the_post_thumbnail('full', ['class' => 'w-full h-auto rounded-lg shadow-lg']); ?>
    </div>
<?php endif; ?>


<div class="container">

    <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-200 w-full mb-14">
        <div class="stat">
            <div class="stat-title">Made In</div>
            <div class="stat-value text-lg font-normal"><?php echo $json['made_in']; ?></div>
        </div>

        <div class="stat">
            <div class="stat-title">Bioengineered</div>
            <div class="stat-value text-lg font-normal">Yes</div>
            <div class="stat-desc">1 or more ingredients</div>
        </div>
    </div>

    <?php if ($json['allergies']):?>

    <h2 class="text-xl mb-3">Allergy Information</h2>
    <p class="text-sm mb-5">Be aware of potential allergens found in this product.</p>
    <div class="bg-base-200 border-base-300 rounded-box p-5 mb-14">
        <div class="card card-compact bg-base-100 shadow-lg mb-4">
            <div class="card-body">
                <?php foreach($json['allergies'] as $allergy): ?>
                    <h3 class="card-title mt-0 text-lg font-normal">
                        <div class="badge badge-error badge-xs"></div>
                        <?php echo $allergy; ?>
                    </h3>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php endif; ?>

    <h2 class="text-xl mb-3">Ingredients</h2>
    <p class="text-sm mb-5">Not comprehensive.</p>
    <div class="bg-base-200 border-base-200 rounded-box p-5 mb-14">

        <?php foreach($json['ingredients'] as $ingredient): ?>
            <div class="card card-compact bg-base-100 shadow-lg mb-8 pb-2">
                <div class="card-body">
                    <h3 class="card-title mt-0 text-lg font-normal">
                        <?php if ($ingredient['is_neutral']): ?>
                            <div class="badge badge-ghost badge-xs"></div>
                        <?php else: ?>
                            <div class="badge badge-error badge-xs"></div>
                        <?php endif; ?>
                        <?php echo $ingredient['name']; ?>
                    </h3>
                    <p>
                        <?php echo $ingredient['description']; ?>
                    </p>
                    <?php if ($ingredient['is_neutral'] == 0): ?>
                        <p class="text-error">
                            <?php print $ingredient['reason']; ?>
                        </p>
                    <?php else: ?>
                        <p class="text-success">
                            <?php print $ingredient['reason']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($json['manufactured_facility_ingredients']):?>

<h2 class="text-xl mb-3">Shared Facility Ingredients</h2>
<p class="text-sm mb-5">Ingredients that are manufactured in the same facility as the product.</p>
<div class="bg-base-200 border-base-300 rounded-box p-5 mb-14">
    <div class="card card-compact bg-base-100 shadow-lg mb-4">
        <div class="card-body">
            <?php foreach($json['manufactured_facility_ingredients'] as $allergy): ?>
                <h3 class="card-title mt-0 text-lg font-normal">
                    <div class="badge badge-warning badge-xs"></div>
                    <?php echo $allergy; ?>
                </h3>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php endif; ?>




    <?php pr($json); ?>

</div>

<?php get_footer(); ?>