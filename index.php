<?php
// Define the URL and headers
$url = 'endpoint.php'; // Replace with your own URL
$api_key = 'WebilyKeys'; // Replace with your own API key

// Prepare the POST data
$post_data = [
    'apikey' => $api_key
];

// Initialize a cURL session
$ch = curl_init($url);

// Configure cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

// Execute the cURL session and receive the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Parse the response (assuming it's a JSON response)
    $response_data = json_decode($response, true);
    
    // Process the result
    if ($response_data['status'] === 'success') {
        $data = $response_data['data'];
        foreach ($data as $item) {
            echo 'ID: ' . $item['id'] . '<br>';
            echo 'Title: ' . $item['title'] . '<br>';
            echo 'Description: ' . $item['desc'] . '<br>';
            echo 'Updated at: ' . $item['updated_at'] . '<br>';
            echo 'Created at: ' . $item['created_at'] . '<br>';
            echo '<br>'; // Add a line break between each result
        }
    } else {
        echo 'Error: ' . $response_data['message'];
    }
}

// Close the cURL session
curl_close($ch);
?>
