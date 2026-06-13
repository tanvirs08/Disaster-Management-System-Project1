<?php
include "config/db.php";
include "includes/header.php";

$city = "Chattogram";
$latitude = 22.3569;
$longitude = 91.7832;

if (isset($_GET['city'])) {
    $selected = $_GET['city'];

    if ($selected == "Dhaka") {
        $city = "Dhaka";
        $latitude = 23.8103;
        $longitude = 90.4125;
    } elseif ($selected == "Chattogram") {
        $city = "Chattogram";
        $latitude = 22.3569;
        $longitude = 91.7832;
    } elseif ($selected == "Cox's Bazar") {
        $city = "Cox's Bazar";
        $latitude = 21.4272;
        $longitude = 92.0058;
    } elseif ($selected == "Sylhet") {
        $city = "Sylhet";
        $latitude = 24.8949;
        $longitude = 91.8687;
    } elseif ($selected == "Khulna") {
        $city = "Khulna";
        $latitude = 22.8456;
        $longitude = 89.5403;
    } elseif ($selected == "Barishal") {
        $city = "Barishal";
        $latitude = 22.7010;
        $longitude = 90.3535;
    } elseif ($selected == "Rajshahi") {
        $city = "Rajshahi";
        $latitude = 24.3745;
        $longitude = 88.6042;
    } elseif ($selected == "Rangpur") {
        $city = "Rangpur";
        $latitude = 25.7439;
        $longitude = 89.2752;
    }
}

$api_url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max,precipitation_sum,wind_speed_10m_max,weather_code&forecast_days=10&timezone=auto";

$response = @file_get_contents($api_url);
$data = json_decode($response, true);

function getRiskLevel($rainChance, $rainSum, $windSpeed) {
    if ($rainChance >= 80 || $rainSum >= 80 || $windSpeed >= 70) {
        return "Extreme";
    } elseif ($rainChance >= 60 || $rainSum >= 50 || $windSpeed >= 50) {
        return "High";
    } elseif ($rainChance >= 40 || $rainSum >= 20 || $windSpeed >= 30) {
        return "Medium";
    } else {
        return "Low";
    }
}
?>

<div class="dashboard">
    <h1>10 Days Weather Forecast</h1>
    <p>Forecast with AI-based disaster risk level.</p>

    <form method="GET" style="margin-top:20px; margin-bottom:20px;">
        <select name="city" style="padding:12px; width:260px;">
            <option value="Chattogram" <?php if($city=="Chattogram") echo "selected"; ?>>Chattogram</option>
            <option value="Dhaka" <?php if($city=="Dhaka") echo "selected"; ?>>Dhaka</option>
            <option value="Cox's Bazar" <?php if($city=="Cox's Bazar") echo "selected"; ?>>Cox's Bazar</option>
            <option value="Sylhet" <?php if($city=="Sylhet") echo "selected"; ?>>Sylhet</option>
            <option value="Khulna" <?php if($city=="Khulna") echo "selected"; ?>>Khulna</option>
            <option value="Barishal" <?php if($city=="Barishal") echo "selected"; ?>>Barishal</option>
            <option value="Rajshahi" <?php if($city=="Rajshahi") echo "selected"; ?>>Rajshahi</option>
            <option value="Rangpur" <?php if($city=="Rangpur") echo "selected"; ?>>Rangpur</option>
        </select>

        <button type="submit" class="btn">Show Forecast</button>
    </form>

    <h2>Location: <?php echo $city; ?></h2>

    <table class="data-table">
        <tr>
            <th>Date</th>
            <th>Max Temp</th>
            <th>Min Temp</th>
            <th>Rain Chance</th>
            <th>Rainfall</th>
            <th>Wind Speed</th>
            <th>Weather Code</th>
            <th>Risk</th>
        </tr>

        <?php
        if ($data && isset($data['daily'])) {
            $daily = $data['daily'];

            for ($i = 0; $i < count($daily['time']); $i++) {
                $date = $daily['time'][$i];
                $max_temp = $daily['temperature_2m_max'][$i];
                $min_temp = $daily['temperature_2m_min'][$i];
                $rain_chance = $daily['precipitation_probability_max'][$i];
                $rainfall = $daily['precipitation_sum'][$i];
                $wind = $daily['wind_speed_10m_max'][$i];
                $code = $daily['weather_code'][$i];

                $risk = getRiskLevel($rain_chance, $rainfall, $wind);

                $check = mysqli_query($conn, "SELECT * FROM forecast_data WHERE location='$city' AND forecast_date='$date'");
                if (mysqli_num_rows($check) == 0) {
                    $insert = "INSERT INTO forecast_data 
                    (location, forecast_date, max_temp, min_temp, rain_chance, wind_speed, condition_text, risk_level)
                    VALUES
                    ('$city', '$date', '$max_temp', '$min_temp', '$rain_chance', '$wind', '$code', '$risk')";
                    mysqli_query($conn, $insert);
                }
        ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $max_temp; ?> °C</td>
                    <td><?php echo $min_temp; ?> °C</td>
                    <td><?php echo $rain_chance; ?>%</td>
                    <td><?php echo $rainfall; ?> mm</td>
                    <td><?php echo $wind; ?> km/h</td>
                    <td><?php echo $code; ?></td>
                    <td><span class="risk-badge <?php echo strtolower($risk); ?>"><?php echo $risk; ?></span></td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='8'>Unable to load forecast data.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>