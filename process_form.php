<?php
// Set the content type to HTML and charset to UTF-8
header('Content-Type: text/html; charset=utf-8');

// --- 1. CHECK IF THE FORM WAS SUBMITTED VIA POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 2. COLLECT AND SANITIZE INPUT DATA ---
    // Use htmlspecialchars() to prevent Cross-Site Scripting (XSS) attacks
    // Use the 'name' attributes from your HTML form fields:
    
    $production_module = htmlspecialchars($_POST['production_module'] ?? '', ENT_QUOTES, 'UTF-8');
    $machine_name      = htmlspecialchars($_POST['machine_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $asset_id          = htmlspecialchars($_POST['asset_id'] ?? '', ENT_QUOTES, 'UTF-8');
    $building          = htmlspecialchars($_POST['building'] ?? 'Building A', ENT_QUOTES, 'UTF-8'); // 'Building A' is likely a default/fixed value
    $area              = htmlspecialchars($_POST['area'] ?? '', ENT_QUOTES, 'UTF-8');
    $line_model        = htmlspecialchars($_POST['line_model'] ?? '', ENT_QUOTES, 'UTF-8');

    // --- 3. PROCESS/STORE THE DATA (Example: Save to a File) ---
    // You can replace this section with database connection code (MySQL, PostgreSQL, etc.)
    
    $data_entry = [
        date("Y-m-d H:i:s"),
        $production_module,
        $machine_name,
        $asset_id,
        $building,
        $area,
        $line_model
    ];
    
    $file_path = 'submissions.csv';
    $file = fopen($file_path, 'a'); // Open file in append mode ('a')
    
    // Check if the file is empty to write the header row only once
    if (filesize($file_path) == 0) {
        $header = ['Timestamp', 'Production Module', 'Machine Name', 'Asset No./ID', 'Building', 'Area', 'Line/Model'];
        fputcsv($file, $header);
    }
    
    // Write the new data entry
    fputcsv($file, $data_entry);
    fclose($file);
    
    // --- 4. DISPLAY SUCCESS MESSAGE ---
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Submission Confirmed</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #FFFF00; text-align: center; padding: 50px; }
            .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: inline-block; text-align: left; }
            h2 { color: #0000FF; }
            ul { list-style-type: none; padding: 0; }
            li { margin-bottom: 10px; }
            strong { display: inline-block; width: 150px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>✅ Submission Successful!</h2>
            <p>The **Online Machine Safety and Assessment Qualification** details have been recorded.</p>
            <p>--- Submitted Data ---</p>
            <ul>
                <li><strong>Production Module:</strong> <?php echo $production_module; ?></li>
                <li><strong>Machine Name:</strong> <?php echo $machine_name; ?></li>
                <li><strong>Asset No./ID:</strong> <?php echo $asset_id; ?></li>
                <li><strong>Building:</strong> <?php echo $building; ?></li>
                <li><strong>Specified Area:</strong> <?php echo $area; ?></li>
                <li><strong>Line or Model:</strong> <?php echo $line_model; ?></li>
            </ul>
            <a href="machine_form.html">Return to Form</a>
        </div>
    </body>
    </html>

<?php
} else {
    // If the page is accessed directly without POST data
    echo "<p style='color: red;'>Access Denied. Please submit the form via the main page.</p>";
}
?>