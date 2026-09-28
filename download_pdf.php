<?php
// Include the PDF logger script
require_once 'pdf_logger.php';

// Get the requested PDF file
$file = $_GET['file'];

// Set the PDF file path
$pdf_file = './' . $file;

// Check if the PDF file exists
if (file_exists($pdf_file)) {
    // Log the PDF open activity
    log_pdf_access('pdf_access_log.txt', $_SERVER['REMOTE_ADDR'], get_location($_SERVER['REMOTE_ADDR']), $file, 'Opened');

    // Set the Content-Type header to application/pdf
    header('Content-Type: application/pdf');

    // Set the Content-Disposition header to include the original filename
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');

    // Read the PDF file and output its contents
    readfile($pdf_file);
    exit;
} else {
    // Handle the case when the PDF file is not found
    header('HTTP/1.0 404 Not Found');
    echo 'PDF file not found.';
    exit;
}
?>