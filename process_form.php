<?php
// Set the content type to HTML and charset to UTF-8
header('Content-Type: text/html; charset=utf-8');

// Define a variable for the file path
$file_path = 'submissions.csv';

// --- 1. CHECK IF THE FORM WAS SUBMITTED VIA POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 2. COLLECT AND SANITIZE INPUT DATA ---
    // Update the variable names and the $_POST keys to match the 'name' attributes from index.html:
    // division, machine-name, asset-id, building-location, area-id, line-model
    
    $division          = htmlspecialchars($_POST['division'] ?? '', ENT_QUOTES, 'UTF-8');
    $machine_name      = htmlspecialchars($_POST['machine-name'] ?? '', ENT_QUOTES, 'UTF-8');
    $asset_id          = htmlspecialchars($_POST['asset-id'] ?? '', ENT_QUOTES, 'UTF-8');
    $building_location = htmlspecialchars($_POST['building-location'] ?? '', ENT_QUOTES, 'UTF-8');
    $area_id           = htmlspecialchars($_POST['area-id'] ?? '', ENT_QUOTES, 'UTF-8');
    $line_model        = htmlspecialchars($_POST['line-model'] ?? '', ENT_QUOTES, 'UTF-8'); // This field is optional/nullable

    // --- 3. PROCESS/STORE THE DATA (Saving to CSV File) ---
    
    $data_entry = [
        date("Y-m-d H:i:s"),
        $division,
        $machine_name,
        $asset_id,
        $building_location,
        $area_id,
        $line_model
    ];
    
    // Attempt to open the file in append mode ('a')
    $file = fopen($file_path, 'a');
    
    if ($file) {
        // Check if the file is empty to write the header row only once
        if (filesize($file_path) == 0) {
            $header = ['Timestamp', 'Division', 'Machine Name', 'Asset No./ID', 'Building Location', 'Specified Area/ID', 'Line/Model'];
            fputcsv($file, $header);
        }
        
        // Write the new data entry
        fputcsv($file, $data_entry);
        fclose($file);
    }
    
    // --- 4. DISPLAY SUCCESS MESSAGE ---
    // Note: I've updated the variable names in the success message too.
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
            strong { display: inline-block; width: 170px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>✅ Submission Successful!</h2>
            <p>The **Online Machine Safety and Assessment Qualification** details have been recorded.</p>
            <p>--- Submitted Data ---</p>
            <ul>
                <li><strong>Division/Module:</strong> <?php echo $division; ?></li>
                <li><strong>Machine Name:</strong> <?php echo $machine_name; ?></li>
                <li><strong>Asset No./ID:</strong> <?php echo $asset_id; ?></li>
                <li><strong>Building Location:</strong> <?php echo $building_location; ?></li>
                <li><strong>Specified Area/ID:</strong> <?php echo $area_id; ?></li>
                <li><strong>Line or Model:</strong> <?php echo $line_model; ?></li>
            </ul>
            <a href="index.html">Return to Form</a>
        </div>
    </body>
    </html>

<?php
} else {
    // If the page is accessed directly without POST data
    echo "<p style='color: red;'>Access Denied. Please submit the form via the main page.</p>";
}
?>
