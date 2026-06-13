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

$api_url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current=temperature_2m,relative_humidity_2m,precipitation,rain,weather_code,wind_speed_10m,pressure_msl&timezone=auto";

$response = @file_get_contents($api_url);
$data = json_decode($response, true);

$temp = "N/A";
$humidity = "N/A";
$rain = "N/A";
$wind = "N/A";
$pressure = "N/A";
$weather_code = "N/A";
$risk = "Low";

if ($data && isset($data['current'])) {
    $temp = $data['current']['temperature_2m'];
    $humidity = $data['current']['relative_humidity_2m'];
    $rain = $data['current']['rain'];
    $wind = $data['current']['wind_speed_10m'];
    $pressure = $data['current']['pressure_msl'];
    $weather_code = $data['current']['weather_code'];

    if ($rain >= 80 || $wind >= 70) {
        $risk = "Extreme";
    } elseif ($rain >= 50 || $wind >= 50) {
        $risk = "High";
    } elseif ($rain >= 20 || $wind >= 30) {
        $risk = "Medium";
    } else {
        $risk = "Low";
    }

    $insert = "INSERT INTO weather_data 
    (location, temperature, humidity, wind_speed, pressure, rainfall, weather_condition)
    VALUES 
    ('$city', '$temp', '$humidity', '$wind', '$pressure', '$rain', '$weather_code')";

    mysqli_query($conn, $insert);
}
?>

<div class="dashboard">
    <h1>Live Weather Update</h1>
    <p>Current weather information for selected Bangladesh cities.</p>

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

        <button type="submit" class="btn">Check Weather</button>
    </form>

    <div class="cards">
        <div class="card">
            <h3>Location</h3>
            <h2><?php echo $city; ?></h2>
        </div>

        <div class="card">
            <h3>Temperature</h3>
            <h2><?php echo $temp; ?> °C</h2>
        </div>

        <div class="card">
            <h3>Humidity</h3>
            <h2><?php echo $humidity; ?>%</h2>
        </div>

        <div class="card">
            <h3>Rainfall</h3>
            <h2><?php echo $rain; ?> mm</h2>
        </div>

        <div class="card">
            <h3>Wind Speed</h3>
            <h2><?php echo $wind; ?> km/h</h2>
        </div>

        <div class="card">
            <h3>Pressure</h3>
            <h2><?php echo $pressure; ?> hPa</h2>
        </div>

        <div class="card">
            <h3>Weather Code</h3>
            <h2><?php echo $weather_code; ?></h2>
        </div>

        <div class="card risk-<?php echo strtolower($risk); ?>">
            <h3>AI Risk Level</h3>
            <h2><?php echo $risk; ?></h2>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>