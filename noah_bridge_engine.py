"""
Project: Noah-Bridge Lite Gemini
Description: An ultra-lightweight AI proxy designed for legacy devices 
             (Samsung Galaxy Note 1/2/3, iPhone 3GS/4/4S, etc.).
             This engine bridges the gap between old hardware and modern intelligence.
Vision: "AI for Everyone, No One Left Behind"
License: GNU General Public License v3.0 (GPL-3.0)
Author: Noah & Partners
Organization: Noah-Bridge DAO (Decentralized Autonomous Organization)
"""

from flask import Flask, render_template_string, request
import google.generativeai as genai
import os

app = Flask(__name__)

# [Security] Fetch API Key from environment variables.
# Usage: export GEMINI_API_KEY="your_actual_key_here"
API_KEY = os.environ.get("GEMINI_API_KEY", "YOUR_API_KEY_HERE")
genai.configure(api_key=API_KEY)
model = genai.GenerativeModel('gemini-1.5-flash')

# Optimized for WebKit (Early iOS) and Android 2.3+ Browsers
HTML_TEMPLATE = """
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noah's AI Bridge</title>
    <style>
        body { background-color: #ffffff; color: #000000; font-family: sans-serif; margin: 15px; }
        .container { max-width: 480px; margin: 0 auto; border: 1px solid #000; padding: 12px; }
        .header { border-bottom: 2px solid #000; margin-bottom: 15px; font-weight: bold; font-size: 1.1em; }
        .chat-box { background: #f4f4f4; border: 1px inset #ccc; padding: 10px; min-height: 120px; margin-bottom: 15px; white-space: pre-wrap; font-size: 14px; line-height: 1.5; }
        .input-box { width: 92%; padding: 10px; margin-bottom: 10px; border: 1px solid #333; }
        .btn { width: 100%; padding: 12px; background: #000000; color: #ffffff; border: none; font-weight: bold; cursor: pointer; }
        .footer { font-size: 0.8em; color: #666; text-align: center; margin-top: 25px; border-top: 1px dotted #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Noah's AI Partner (Lite)</div>
        <div class="chat-box">{{ response }}</div>
        <form method="POST">
            <input type="text" name="message" class="input-box" placeholder="What would you like to ask?" autofocus>
            <button type="submit" class="btn">Send Request</button>
        </form>
        <div class="footer">
            "Connecting the past to the future." <br>
            Powered by Noah-Bridge DAO (GPL 3.0)
        </div>
    </div>
</body>
</html>
"""

@app.route("/", methods=["GET", "POST"])
def index():
    # Standard greeting for legacy device users
    response_text = "Welcome! How can I help you today?"
    
    if request.method == "POST":
        user_msg = request.form.get("message")
        if user_msg:
            try:
                # Optimized for speed with Gemini 1.5 Flash
                result = model.generate_content(user_msg)
                response_text = result.text
            except Exception as e:
                response_text = "System Notice: Unable to reach AI servers. Please check your hosting environment or API quota."
    
    return render_template_string(HTML_TEMPLATE, response=response_text)

if __name__ == "__main__":
    # Host on 0.0.0.0 to enable local network access for old smartphones
    app.run(host='0.0.0.0', port=5000)
