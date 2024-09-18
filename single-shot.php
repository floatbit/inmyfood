<?php get_header(); ?>

<?php
    $chatgpt_response = get_field('chatgpt_response');
    $json = json_decode($chatgpt_response, true);
?>

<?php if (has_post_thumbnail()): ?>
    <div class="mb-10">
        <?php the_post_thumbnail('full', ['class' => 'w-full h-auto shadow-lg']); ?>
    </div>
<?php endif; ?>

    <?php if ($json['allergies']): ?>
        <div class="container">
            <h2 class="text-xl mb-3">Allergy Information</h2>
            <p class="text-sm mb-5">Be aware of potential allergens found in this product.</p>
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

    <div class="divider my-8"></div>
    
    <div class="container">
        <h2 class="text-xl mb-3">Ingredients</h2>
        <p class="text-sm mb-5">Note: This list may not include all ingredients. Please refer to the packaging for a complete list.</p>

        <div class="ingredients">
            <?php foreach($json['ingredients'] as $ingredient): ?>
                <div class="card card-compact bg-base-100 shadow-lg mb-8 pb-2 relative">
                    <div class="card-body">
                        <h3 class="card-title mt-0 text-lg font-normal">
                            <?php if ($ingredient['is_neutral']): ?>
                                <div class="badge badge-ghost badge-xs absolute top-0 left-0 hidden"></div>
                            <?php else: ?>
                                <div class="badge badge-error badge-xs absolute top-[-5px] left-[-5px]"></div>
                            <?php endif; ?>
                            <?php echo $ingredient['name']; ?>
                        </h3>
                        <p>
                            <?php echo $ingredient['description']; ?>
                        </p>
                        <?php if ($ingredient['is_neutral'] == 0): ?>
                            <p class="text-xs text-error">
                                <?php print $ingredient['reason']; ?>
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-success">
                                <?php print $ingredient['reason']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($json['manufactured_facility_ingredients']): ?>
        <div class="divider my-8"></div>

        <div class="container">
            <h2 class="text-xl mb-3">Shared Facility Ingredients</h2>
            <p class="text-sm mb-5">Ingredients that are manufactured in the same facility as the product.</p>
            <div class="ingredients-facility">
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
        </div>
    <?php endif; ?>

    <?php if ($json['made_in'] || $json['bioengineered']): ?>
        <div class="divider my-8"></div>
        <div class="container pb-8">
            <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-300 w-full">
                <div class="stat">
                    <div class="stat-title">Made In</div>
                    <div class="stat-value text-lg font-normal"><?php echo $json['made_in']; ?></div>
                </div>

                <?php if ($json['bioengineered']): ?>
                    <div class="stat">
                        <div class="stat-title">Bioengineered</div>
                        <div class="stat-value text-lg font-normal">Yes</div>
                        <div class="stat-desc">1 or more ingredients</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>


<?php if (isset($_GET['c'])) pr($json); ?>


<?php get_footer(); ?>