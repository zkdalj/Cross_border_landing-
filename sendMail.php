<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer 
// Check if form data exists

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$state = isset($_POST['state']) ? $_POST['state'] : '';
$company = isset($_POST['company']) ? $_POST['company'] : '';
$url = isset($_POST['url']) ? $_POST['url'] : '';

$utm_source = isset($_POST['utm_source']) ? $_POST['utm_source'] : 'default_source';
$utm_medium = isset($_POST['utm_medium']) ? $_POST['utm_medium'] : 'default_medium';
$utm_campaign = isset($_POST['utm_campaign']) ? $_POST['utm_campaign'] : 'default_campaign';
$utm_term = isset($_POST['utm_term']) ? $_POST['utm_term'] : '';
$utm_content = isset($_POST['utm_content']) ? $_POST['utm_content'] : '';
$gcl_id = isset($_POST['gclid']) ? $_POST['gclid'] : '';


// for Odoo API Endpoint start

function sendToOdoo($apiUrl, $payload)
{
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    // Handle SSL issues
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Allow redirects if needed
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$odooApiUrl = "https://odoo.concertosoft.com/customer/lead";

// Contact Form Data (Replace with actual form inputs)
$leadData = [
    "name" => $name,
    "email" => $email,
    "mobile" => $mobile,
    "message" => $message,
    "state" => $state,
    "company" => $company,
    "url" => $url,
    "utm_source" => $utm_source,
    "utm_medium" => $utm_medium,
    "utm_campaign" => $utm_campaign,
    "utm_term" => $utm_term,
    "utm_content" => $utm_content,
    "gcl_id" => $gcl_id
];

// Send Data to Odoo

$jsonPayload = json_encode($leadData);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Error: " . json_last_error_msg());
}
$apiResponse = sendToOdoo($odooApiUrl, $leadData);

// for Odoo API Endpoint end


// Validate required fields
if (empty($name) || empty($email) || empty($mobile)) {
    echo "All required fields must be filled!";
    echo $name;
    exit;
}

// Email content
$userMessage = "
    <h3>Dear $name,</h3>
    <p>Thank you for contacting us. We have received your message and will get back to you soon.</p>
    <p><strong>Your Details:</strong></p>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Mobile:</strong> $mobile</p>
    <p><strong>Message:</strong> $message</p>
    <p><strong>State:</strong> $state</p>
    <p><strong>Company Name:</strong> $company</p>
    <p><strong>Company URL:</strong> $url</p>
    <p>Best Regards,<br>Support Team</p>
";

$adminMessage = "
    <h3>New Contact Form Submission</h3>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Mobile:</strong> $mobile</p>
    <p><strong>Message:</strong> $message</p>
    <p><strong>State:</strong> $state</p>
    <p><strong>Company Name:</strong> $company</p>
    <p><strong>Company URL:</strong> $url</p>
    <p><strong>UTM Source:</strong> $utm_source</p>
    <p><strong>UTM medium:</strong> $utm_medium </p>
    <p><strong>UTM Campaign:</strong> $utm_campaign </p>
    <p><strong> GCLID: </strong> $gcl_id  </p>
";

try {
  

    echo "SUCCESS";
} catch (Exception $e) {
    echo "ERROR";
}
?>