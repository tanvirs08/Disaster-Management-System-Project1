<?php
include "config/db.php";
include "includes/header.php";

$result_message = "";
$predicted_disaster = "";
$risk_level = "";
$risk_percentage = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $temperature = floatval($_POST['temperature']);
    $humidity = floatval($_POST['humidity']);
    $rainfall = floatval($_POST['rainfall']);
    $wind_speed = floatval($_POST['wind_speed']);
    $pressure = floatval($_POST['pressure']);

    $score = 0;
    $predicted_disaster = "No Major Disaster";

    if ($rainfall >= 100) {
        $score += 40;
        $predicted_disaster = "Flood / Heavy Rain Risk";
    } elseif ($rainfall >= 60) {
        $score += 30;
        $predicted_disaster = "Heavy Rain Risk";
    } elseif ($rainfall >= 30) {
        $score += 20;
    }

    if ($wind_speed >= 80) {
        $score += 35;
        $predicted_disaster = "Cyclone / Storm Risk";
    } elseif ($wind_speed >= 50) {
        $score += 25;
        $predicted_disaster = "Storm Risk";
    } elseif ($wind_speed >= 30) {
        $score += 15;
    }

    if ($temperature >= 38) {
        $score += 20;
        $predicted_disaster = "Heatwave Risk";
    } elseif ($temperature >= 34) {
        $score += 10;
    }

    if ($humidity >= 85 && $rainfall >= 50) {
        $score += 15;
    }

    if ($pressure <= 990 && $wind_speed >= 50) {
        $score += 20;
        $predicted_disaster = "Cyclone Risk";
    }

    if ($score > 100) {
        $score = 100;
    }

    $risk_percentage = $score;

    if ($score >= 80) {
        $risk_level = "Extreme";
    } elseif ($score >= 60) {
        $risk_level = "High";
    } elseif ($score >= 35) {
        $risk_level = "Medium";
    } else {
        $risk_level = "Low";
    }

    $sql = "INSERT INTO ai_predictions
            (location, temperature, humidity, rainfall, wind_speed, pressure, predicted_disaster, risk_percentage, risk_level)
            VALUES
            ('$location', '$temperature', '$humidity', '$rainfall', '$wind_speed', '$pressure', '$predicted_disaster', '$risk_percentage', '$risk_level')";

    mysqli_query($conn, $sql);

    $result_message = "
        <div class='prediction-result'>
            <h2>Prediction Result</h2>
            <p><strong>Location:</strong> $location</p>
            <p><strong>Predicted Disaster:</strong> $predicted_disaster</p>
            <p><strong>Risk Level:</strong> <span class='risk-badge ".strtolower($risk_level)."'>$risk_level</span></p>
            <p><strong>Risk Percentage:</strong> $risk_percentage%</p>
        </div>
    ";
}
?>

<div class="dashboard">
    <h1>AI Disaster Risk Prediction</h1>
    <p>Enter weather parameters to predict disaster risk level.</p>

    <div class="form-container">
        <h2>Weather Input Form</h2>

        <form method="POST">
<input type="text" name="location" list="districtList" placeholder="Select or type district" required>
<?php include "includes/districts.php"; ?>
            <input type="number" step="0.01" name="temperature" placeholder="Temperature °C" required>

            <input type="number" step="0.01" name="humidity" placeholder="Humidity %" required>

            <input type="number" step="0.01" name="rainfall" placeholder="Rainfall mm" required>

            <input type="number" step="0.01" name="wind_speed" placeholder="Wind Speed km/h" required>

            <input type="number" step="0.01" name="pressure" placeholder="Pressure hPa" required>

            <button type="submit" class="btn">Predict Risk</button>
        </form>
    </div>

    <?php echo $result_message; ?>
</div>

<?php include "includes/footer.php"; ?>