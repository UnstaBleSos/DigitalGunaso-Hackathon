<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;

include("./dbconnect.php");

// Initialize the Gemini client
$client = new Client('AIzaSyDfs7fpd8heH27Pdv9_m5hpK6Qp64lT_Ss');

// Retrieve form data
$complaint = $_POST['complaint'];
$category = $_POST['category'];
$subcategory = $_POST['subcategory'];

// Define hotlines for subcategories
$hotlines = [
    'Electricity' => [
        'Transmitter burst' => 'Electricity Hotline: 9841355875',
        'Cut-off wire' => 'Electricity Hotline: 9841355899',
        'Pole fall' => 'Electricity Hotline: 9841355456',
        'Half-cut electricity' => 'Electricity Hotline: 9841312495'
    ],
    'Water' => [
        'Dirty water' => 'Water Hotline: 9875663212',
        'No schedule time water' => 'Water Hotline: 9875663265',
        'Pipe burst' => 'Water Hotline: 9875663288'
    ],
    'Garbage' => [
        'No schedule time' => 'Garbage Hotline: 9841565789',
        'Overload garbage' => 'Garbage Hotline: 9841565755'
    ]
];

// Fetch the hotline based on category and subcategory
$hotline = $hotlines[$category][$subcategory] ?? 'General Emergency: 984512285';

// Send the complaint to LLM for additional analysis
$response = $client->generativeModel(ModelName::GEMINI_PRO)->generateContent(
    new TextPart("The user submitted a complaint under the category '$category' and subcategory '$subcategory'. 
    Complaint: '$complaint'. Please provide any additional insights.")
);

// Retrieve LLM's response
$ai_response = $response->text();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details</title>
    <style>
        /* Popup styling */
        .popup {
            display: block; /* Show popup by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .popup-content {
            background-color: #fff;
            margin: 10% auto; /* Adjust centering */
            padding: 30px;
            border: 1px solid #888;
            width: 90%; /* Increase width */
            max-width: 800px; /* Adjust max width */
            height: auto; /* Allow height to grow as needed */
            max-height: 80%; /* Prevent overflowing */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto; /* Add scrolling for long content */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function closePopup() {
            document.getElementById("complaint-popup").style.display = "none";
        }
    </script>
</head>
<body>
    <h1>Complaint Submission</h1>

    <!-- Popup structure -->
    <div id="complaint-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <p><strong>Complaint:</strong> <?php echo htmlspecialchars($complaint); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
            <p><strong>Subcategory:</strong> <?php echo htmlspecialchars($subcategory); ?></p>
            <p><strong>Hotline:</strong> <?php echo htmlspecialchars($hotline); ?></p>
            <p><strong>AI Response:</strong> <?php echo htmlspecialchars($ai_response); ?></p>
        </div>
    </div>
</body>
</html>

