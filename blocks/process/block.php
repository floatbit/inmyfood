<?php

if (isset($_POST['image'])) {
    $imageData = $_POST['image'];
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageContent = base64_decode($imageData);

    // Generate a unique ID
    $unique_id = uniqid();

    // Create a new post of post type 'shot'
    $post_id = wp_insert_post([
        'post_title' => $unique_id,
        'post_type' => 'shot',
        'post_status' => 'publish',
        'post_author' => 0, // Anonymous author
    ]);

    // Save the image in the media library
    $upload_dir = wp_upload_dir();
    $image_name = $unique_id . '.jpg';
    $image_path = $upload_dir['path'] . '/' . $image_name;

    // Create an image resource from the decoded content
    $image = imagecreatefromstring($imageContent);

    // Save the image as a JPG
    imagejpeg($image, $image_path, 80); // 0-100 quality for JPG, 90 is a good balance

    // Resize the image
    function resizeImage($file, $maxWidth, $maxHeight) {
        list($origWidth, $origHeight) = getimagesize($file);
        $width = $origWidth;
        $height = $origHeight;

        if ($width > $maxWidth) {
            $height = ($maxWidth / $width) * $height;
            $width = $maxWidth;
        }

        if ($height > $maxHeight) {
            $width = ($maxHeight / $height) * $width;
            $height = $maxHeight;
        }

        $src = imagecreatefromjpeg($file); // Assuming JPG as the format
        $dst = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

        return $dst;
    }

    // Resize the image
    $resizedImage = resizeImage($image_path, 800, 600); // Resize to fit within 800x600

    // Save the resized image to a new file
    $resizedImageName = $unique_id . '_resized.jpg';
    $resizedImagePath = $upload_dir['path'] . '/' . $resizedImageName;
    imagejpeg($resizedImage, $resizedImagePath, 90); // 0-100 quality for JPG, 90 is a good balance

    // Add the resized image to the media library
    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $resizedImageName,
        'post_mime_type' => 'image/jpeg',
        'post_title' => sanitize_file_name($resizedImageName),
        'post_content' => '',
        'post_status' => 'inherit'
    ];

    $attachment_id = wp_insert_attachment($attachment, $resizedImagePath, $post_id);

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata($attachment_id, $resizedImagePath);
    wp_update_attachment_metadata($attachment_id, $attach_data);

    // Set the image as the featured image of the post
    set_post_thumbnail($post_id, $attachment_id);

    // do api response
    // Updated prompt to incorporate all suggestions
    $prompt = 'You are a world renowned registered dietician. Analyze this image and extract all ingredients. Return the results as JSON, starting with "{", using this structure: {
        "ingredients": [
          {
            "name": "Soy Sauce",
            "description": "A common condiment made from fermented soybeans, water, and salt.",
            "reason": "High sodium content which can contribute to high blood pressure.",
            "is_neutral": 1,
            "environmental_impact": "Low footprint",
            "nutritional_risks": ["sodium"]
          }
        ],
        "made_in": "",
        "manufactured_facility_ingredients": [],
        "allergies": ["soy"],
        "bioengineered": 0,
        "bioengineered_reason": "",
      }. Avoid listing sub-ingredients. Use sub-ingredients to determine "reason," and "allergies". Provide nutritional risks and environmental impact. Set "is_neutral" to 0 if any nutritional risks are present. Leave "made_in" blank if not shown. Leave "manufactured_facility_ingredients" blank unless facility info is provided. Set "bioengineered" to 1 if any ingredient is bioengineered and provide a "bioengineered_reason" if possible, otherwise 0. Return JSON in one line with no formatting.';

      // Create the JSON payload
      $data = [
          "model" => "gpt-4o",
          "messages" => [
              [
                  "role" => "user",
                  "content" => [
                      [
                          "type" => "text",
                          "text" => $prompt
                      ],
                      [
                          "type" => "image_url",
                          "image_url" => [
                              "url" => wp_get_attachment_image_url($attachment_id, 'full')
                          ]
                      ]
                  ]
              ]
          ],
          "max_tokens" => 2000
      ];

      // API endpoint and API key
      $url = 'https://api.openai.com/v1/chat/completions';
      $apiKey = CHATGPT_API_KEY;

      // Initialize cURL
      $ch = curl_init($url);

      // Set cURL options
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json',
          'Authorization: Bearer ' . $apiKey
      ]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute cURL request and get the response
      $response = curl_exec($ch);

      // Save the response to acf field chatgpt_response
      if ($response !== false) {
        echo $response;
        echo '<br>';
        echo '<br>';
        echo '<br>';
          $json = json_decode($response, true);
          if (isset($json['choices'][0]['message']['content'])) {
            $chatgpt_response = $json['choices'][0]['message']['content'];
            $chatgpt_response_clean = preg_replace('/\s+/', ' ', trim($chatgpt_response));
            update_field('field_66e98115ce90c', $chatgpt_response_clean, $post_id);
          }
      }
      
      // Redirect to the single shot page
      $redirect_url = get_permalink($post_id);
      echo '<script type="text/javascript">';
      echo 'window.location.href="' . $redirect_url . '";';
      echo '</script>';
      exit;
}
?>
