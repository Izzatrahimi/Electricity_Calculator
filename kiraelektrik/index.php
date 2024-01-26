<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Calculator</title>
    <!-- Include Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php

// Function to calculate electricity charge based on voltage, current, hour, and rate
function calculateElectricityCharge($voltage, $current, $hour, $rate) {
    $power = $voltage * $current / 1000; // Convert to kW
    $energy = ($power * $hour); // Energy in kWh
    $totalCharge = $energy * ($rate / 100);
    return [$power, $energy, $totalCharge];
}

// Initial values from the user input
$initialVoltage = isset($_POST['voltage']) ? $_POST['voltage'] : '';
$initialCurrent = isset($_POST['current']) ? $_POST['current'] : '';
$initialRate = isset($_POST['rate']) ? $_POST['rate'] : '';
$calculatedPower = 0;
$calculatedTotalCharge = 0;

?>

<div class='container mt-5'>
    <h1 class='mb-4'>Electricity Bill Calculator</h1>

    <div class='container mt-3'>
        <form method="post">
            <div class="form-group">
                <label for="voltage">Voltage (V): </label>
                <input type="number" step="any" class="form-control" name="voltage" value="<?php echo htmlspecialchars($initialVoltage); ?>" required>
            </div>

            <div class="form-group">
                <label for="current">Current (A): </label>
                <input type="number" step="any" class="form-control" name="current" value="<?php echo htmlspecialchars($initialCurrent); ?>" required>
            </div>

            <div class="form-group">
                <label for="rate">Current Rate (sen/kWh): </label>
                <input type="number" step="any" class="form-control" name="rate" value="<?php echo htmlspecialchars($initialRate); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>
    </div>

    <?php

    // If form is submitted, calculate and display results
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $voltage = $_POST['voltage'];
        $current = $_POST['current'];
        $rate = $_POST['rate'];

        // Display calculated Power (kW) and Current Rate (RM)
        echo "<div class='container mt-3'>";

        // Calculate power and total charge for the first hour
        list($calculatedPower, $energy, $calculatedTotalCharge) = calculateElectricityCharge($voltage, $current, 1, $rate);

        // Display a table with hourly energy consumption and total charge
        echo "<div class='container mt-3'>";
        echo "<div class='alert alert-info'>";
        echo "<h2>Power: " . number_format($calculatedPower, 5) . "kW </h2>";
        echo "<h2>Current Rate: " . number_format($initialRate/100, 3) . "RM </h2>";
        echo "</div>";
        echo "</div>";

        echo "<table class='table table-bordered'>";
        echo "<thead class='thead-dark'>";
        echo "<tr><th>#</th><th>Hour</th><th>Energy (kWh)</th><th>Total Charge (RM)</th></tr>";
        echo "</thead><tbody>";

        $rowNumber = 1;

        // Loop through each hour
        for ($hour = 1; $hour <= 24; $hour++) {
            list($power, $energy, $totalCharge) = calculateElectricityCharge($voltage, $current, $hour, $rate);

            // Display the results in a table row
            echo "<tr>";
            echo "<td>$rowNumber</td>";
            echo "<td>$hour</td>";
            echo "<td>" . number_format($energy, 5) . "</td>";
            echo "<td>" . number_format($totalCharge, 2) . "</td>";
            echo "</tr>";

            // Update calculated total values
            $calculatedPower += $power;
            $calculatedTotalCharge += $totalCharge;

            $rowNumber++;
        }

        echo "</tbody></table>";
        echo "</div>";
    }

    ?>

</div>

<!-- Include Bootstrap 4 JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>