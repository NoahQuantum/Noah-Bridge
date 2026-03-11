<?php
/**
 * Project: Noah-Bridge Lite Gemini (Finube Edition)
 * Description: An ultra-lightweight AI proxy for legacy devices.
 * Features: Optimized for Gemini 2.5, Real-time Diagnostics.
 * License: GNU General Public License v3.0 (GPL-3.0)
 * Author: Noah & Partners
 */

// [Admin Setting] Paste your Gemini API Key here
$api_key = "YOUR_GEMINI_API_KEY_HERE"; 
$display_msg = "Hello! I am Finube, your AI Partner. How can I help you today?";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_input'])) {
    $user_query = htmlspecialchars($_POST['user_input']);
    
    // Stable Endpoint for Gemini 2.5 Flash
    $api_url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $api_key;
    
    $payload = json_encode([
        'contents' => [['parts' => [['text' => $user_query]]]]
    ]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    
    if (is_resource($ch)) { curl_close($ch); }

    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $display_msg = $data['candidates'][0]['content']['parts'][0]['text'];
    } elseif (isset($data['error'])) {
        // Diagnostic feature suggested by Noah
        $display_msg = "Google API Error: " . $data['error']['message'];
    } else {
        $display_msg = "System Notice: Connection established, but no response. Check your API settings.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noah-Bridge (Finube)</title>
    <style>
        body { background-color: #ffffff; color: #000; font-family: sans-serif; margin: 15px; line-height: 1.5; }
        .container { max-width: 480px; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        .header { border-bottom: 2px solid #000; margin-bottom: 15px; font-weight: bold; }
        .chat-box { background: #f4f4f4; border: 1px inset #ccc; padding: 15px; min-height: 120px; margin-bottom: 15px; white-space: pre-wrap; font-size: 14px; }
        .input-box { width: 92%; padding: 12px; border: 1px solid #333; margin-bottom: 10px; font-size: 16px; }
        .btn { width: 100%; padding: 12px; background: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; }
        .footer { font-size: 11px; color: #666; text-align: center; margin-top: 25px; border-top: 1px dotted #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Noah's AI Partner (Lite)</div>
        <div class="chat-box"><?php echo $display_msg; ?></div>
        <form method="post">
            <input type="text" name="user_input" class="input-box" placeholder="Ask Gemini 2.5..." autofocus>
            <button type="submit" class="btn">Send Request</button>
        </form>
        <div class="footer">Powered by Noah-Bridge DAO & Gemini 2.5 Flash</div>
    </div>
</body>
</html>
