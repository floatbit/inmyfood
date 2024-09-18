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
            <div class="card card-compact bg-base-100 mb-4 rounded">
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

    <div class="divider opacity-60 my-8"></div>
    
    <div class="container">
        <h2 class="text-xl mb-3">Ingredients</h2>
        <p class="text-sm mb-5">Note: This list may not include all ingredients. Please refer to the packaging for a complete list.</p>

        <div class="ingredients">
            <?php foreach($json['ingredients'] as $ingredient): ?>
                <div class="card card-compact bg-base-100 mb-7 pb-2 relative rounded">
                    <div class="card-body">
                        <h3 class="card-title mt-0 text-lg font-normal">
                            <?php if ($ingredient['is_neutral']): ?>
                                <div class="badge badge-ghost badge-xs absolute top-0 left-0 hidden"></div>
                            <?php else: ?>
                                <div class="badge badge-error badge-xs absolute top-[-3px] left-[-3px]"></div>
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
        <div class="divider opacity-60 my-8"></div>

        <div class="container">
            <h2 class="text-xl mb-3">Shared Facility Ingredients</h2>
            <p class="text-sm mb-5">Ingredients that are manufactured in the same facility as the product.</p>
            <div class="ingredients-facility">
                <div class="card card-compact bg-base-100 mb-4 rounded">
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
        
        <div class="divider opacity-60 my-8"></div>

        <div class="container">
            <h2 class="text-xl mb-3">Other Information</h2>
            <div class="ingredients-facility">
                <?php if ($json['made_in']): ?>
                <div class="card card-compact bg-base-100 mb-4 rounded">
                    <div class="card-body">
                        <h3 class="card-title mt-0 text-lg font-normal">
                            Made In: <?php echo $json['made_in']; ?>
                        </h3>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($json['bioengineered']): ?>   
                <div class="card card-compact bg-base-100 mb-4 rounded">
                    <div class="card-body">
                        <h3 class="card-title mt-0 text-lg font-normal">
                            Bioengineered: Yes
                        </h3>
                        <p class="text-xs">
                            1 or more ingredients
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>


<?php if (isset($_GET['c'])) pr($json); ?>


<?php get_footer(); ?>