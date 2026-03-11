<?php
/**
 * Project: Noah-Bridge Lite Gemini (PHP Version)
 * Description: An ultra-lightweight AI proxy for legacy devices 
 * (Samsung Galaxy Note 1/2/3, iPhone 3GS/4/4S, etc.).
 * Vision: "AI for Everyone, No One Left Behind"
 * License: GNU General Public License v3.0 (GPL-3.0)
 * Author: Noah & Partners
 * Organization: Noah-Bridge DAO (Decentralized Autonomous Organization)
 */

// [Admin Setting] Paste your Gemini API Key here.
// IMPORTANT: Do not share this key with others.
$api_key = "YOUR_GEMINI_API_KEY_HERE";
$display_msg = "Hello! How can I help you today?";

// Handle the user request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_input'])) {
    $user_query = htmlspecialchars($_POST['user_input']);
    
    // Google Gemini API Endpoint (1.5 Flash for speed)
    $api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;
    
    $payload = json_encode([
        'contents' => [['parts' => [['text' => $user_query]]]]
    ]);

    // Secure communication via cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);

    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $display_msg = $data['candidates'][0]['content']['parts'][0]['text'];
    } else {
        $display_msg = "System Notice: Unable to retrieve AI response. Please check your API key or server configuration.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noah's AI Bridge (Lite)</title>
    <style>
        body { background-color: #ffffff; color: #000000; font-family: sans-serif; margin: 15px; line-height: 1.5; }
        .container { max-width: 480px; margin: 0 auto; border: 1px solid #000; padding: 15px; }
        .header { border-bottom: 2px solid #000; margin-bottom: 15px; font-weight: bold; font-size: 1.1em; }
        .chat-box { background: #f4f4f4; border: 1px inset #ccc; padding: 15px; min-height: 120px; margin-bottom: 15px; white-space: pre-wrap; font-size: 14px; }
        .input-area input { width: 92%; padding: 10px; border: 1px solid #000; margin-bottom: 10px; }
        .btn { width: 100%; padding: 12px; background: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; }
        .footer { font-size: 0.8em; color: #666; text-align: center; margin-top: 25px; border-top: 1px dotted #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Noah's AI Partner (Lite)</div>
        <div class="chat-box"><?php echo $display_msg; ?></div>
        
        <form method="post">
            <div class="input-area">
                <input type="text" name="user_input" placeholder="Ask anything..." autofocus>
                <button type="submit" class="btn">Send Request</button>
            </div>
        </form>

        <div class="footer">
            "Bridging the past and the future." <br>
            Powered by Noah-Bridge DAO (GPL 3.0)
        </div>
    </div>
</body>
</html>
