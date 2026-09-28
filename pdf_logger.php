<?php
// Set the log file path and name
$log_file = 'pdf_access_log.txt';

// Function to log the PDF access activity
function log_pdf_access($log_file, $ip_address, $location, $file, $activity) {
    // Get the current timestamp
    $visit_time = date('Y-m-d H:i:s');

    // Log the PDF access activity
    $log_entry = "$visit_time - $ip_address - $location - $file - $activity\n";

    // Check if the log file exists
    if (file_exists($log_file)) {
        // Read the existing log entries
        $log_entries = file($log_file, FILE_IGNORE_NEW_LINES);

        // Add the new entry to the top
        array_unshift($log_entries, $log_entry);

        // Write the updated log entries back to the file
        file_put_contents($log_file, implode("\n", $log_entries));
    } else {
        // Create the log file and write the first entry
        file_put_contents($log_file, $log_entry);
    }
}
// Function to get the visitor's location using IP geolocation
function get_location($ip_address) {
    $api_url = 'http://ip-api.com/json/' . $ip_address;
    $response = json_decode(file_get_contents($api_url), true);
    return $response['city'] . ', ' . $response['country'];
}
?>