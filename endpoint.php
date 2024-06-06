<?php
// Database credentials
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Connection failed
    $response = [
        'status' => 'error',
        'message' => 'Connection failed.'
    ];
} else {
    // Connection successful
    // Create the "api_keys" table if it doesn't exist
    $sql_api_keys = "CREATE TABLE IF NOT EXISTS api_keys (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        api_key VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($sql_api_keys) === FALSE) {
        $response = [
            'status' => 'error',
            'message' => 'Error creating api_keys table: ' . $conn->error
        ];
    } else {
        // Check if the "api_keys" table is empty, if so, insert a default key
        $sql_check_keys = "SELECT COUNT(*) as count FROM api_keys";
        $result_check_keys = $conn->query($sql_check_keys);
        $row_check_keys = $result_check_keys->fetch_assoc();
        $key_count = $row_check_keys['count'];
        
        if ($key_count == 0) {
            $default_key = "WebilyKeys";
            $sql_insert_key = "INSERT INTO api_keys (api_key) VALUES ('$default_key')";
            if ($conn->query($sql_insert_key) === FALSE) {
                $response = [
                    'status' => 'error',
                    'message' => 'Error inserting default key: ' . $conn->error
                ];
            }
        }
        
        // Create the "data" table if it doesn't exist
        $sql_data = "CREATE TABLE IF NOT EXISTS data (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            `desc` TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            api_key_id INT(6) UNSIGNED,
            FOREIGN KEY (api_key_id) REFERENCES api_keys(id)
        )";
        
        if ($conn->query($sql_data) === FALSE) {
            $response = [
                'status' => 'error',
                'message' => 'Error creating data table: ' . $conn->error
            ];
        } else {
            // Check if the "data" table is empty, if so, insert a default row
            $sql_check_data = "SELECT COUNT(*) as count FROM data";
            $result_check_data = $conn->query($sql_check_data);
            $row_check_data = $result_check_data->fetch_assoc();
            $data_count = $row_check_data['count'];
            
            if ($data_count == 0) {
                $default_title = "Webily";
                $default_api_key_id = 1; // Assuming 1 is the ID of the default API key
                $sql_insert_data = "INSERT INTO data (title, api_key_id) VALUES ('$default_title', '$default_api_key_id')";
                if ($conn->query($sql_insert_data) === FALSE) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Error inserting default data: ' . $conn->error
                    ];
                }
            }
            
            // Get the expected API keys from the database
            $sql_keys = "SELECT api_key FROM api_keys";
            $result_keys = $conn->query($sql_keys);
            
            if ($result_keys->num_rows > 0) {
                // Populate the array with API keys
                $expected_api_keys = [];
                while($row_keys = $result_keys->fetch_assoc()) {
                    $expected_api_keys[] = $row_keys["api_key"];
                }
                
                // Check request type and API key validity
                if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
                    $received_api_key = isset($_POST['apikey']) ? $_POST['apikey'] : (isset($_GET['apikey']) ? $_GET['apikey'] : '');
                    if (in_array($received_api_key, $expected_api_keys)) {
                        // Valid API key
                        // Retrieve data from the "data" table
                        $data_response = [];
                        $sql_data = "SELECT id, title, `desc`, updated_at, created_at FROM data WHERE api_key_id = (SELECT id FROM api_keys WHERE api_key = '$received_api_key')";
                        $result_data = $conn->query($sql_data);
                        
                        if ($result_data->num_rows > 0) {
                            // Populate the array with data
                            while($row_data = $result_data->fetch_assoc()) {
                                $data_response[] = $row_data;
                            }
                            // Send data response
                            $response = [
                                'status' => 'success',
                                'message' => 'API key is valid.',
                                'data' => $data_response
                            ];
                        } else {
                            // No data found in the "data" table
                            $response = [
                                'status' => 'error',
                                'message' => 'No data has been found at the moment.'
                            ];
                        }
                    } else {
                        // Invalid API key
                        $response = [
                            'status' => 'error',
                            'message' => 'Invalid API key.'
                        ];
                    }
                } else {
                    // Invalid request type
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid request type.'
                    ];
                }
            } else {
                // No API keys found in the database
                $response = [
                    'status' => 'error',
                    'message' => 'No API keys found in the database.'
                ];
            }
        }
    }
    // Close connection
    $conn->close();
}

// Send the response back as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
