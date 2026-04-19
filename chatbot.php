<?php
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripZone AI Assistant - Smart Chatbot</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-dark: #0F4C5C;
            --primary-teal: #2A9D8F;
            --primary-coral: #E76F51;
            --primary-sand: #F4A261;
            --dark-text: #1C2E2A;
            --gray-text: #5A6E66;
            --white: #FFFFFF;
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.1);
            --shadow-lg: 0 16px 32px rgba(0,0,0,0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(145deg, #e0f0ea 0%, #c9ddd3 100%);
            height: 100vh;
            overflow: hidden;
        }

        .chatbot-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .chatbot-card {
            width: 100%;
            max-width: 580px;
            height: 85vh;
            background: white;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            animation: fadeInUp 0.4s ease;
        }

        /* Header */
        .chatbot-card-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-teal));
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bot-avatar {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }

        .header-info h2 {
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .header-info p {
            color: rgba(255,255,255,0.85);
            font-size: 0.65rem;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            background: #4ade80;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        .back-btn {
            background: rgba(255,255,255,0.15);
            border: none;
            padding: 7px 14px;
            border-radius: 40px;
            color: white;
            cursor: pointer;
            font-size: 0.75rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(-2px);
        }

        /* Messages Area */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            background: #f8fafc;
        }

        .messages-area::-webkit-scrollbar {
            width: 5px;
        }

        .messages-area::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .messages-area::-webkit-scrollbar-thumb {
            background: var(--primary-teal);
            border-radius: 10px;
        }

        /* Bot Message */
        .bot-msg {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            max-width: 90%;
            animation: slideInLeft 0.3s ease;
        }

        .bot-avatar-small {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-teal));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: white;
            flex-shrink: 0;
        }

        .bot-bubble {
            background: white;
            padding: 12px 16px;
            border-radius: 20px;
            border-top-left-radius: 5px;
            color: var(--dark-text);
            font-size: 0.85rem;
            line-height: 1.5;
            box-shadow: var(--shadow-sm);
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        /* User Message */
        .user-msg {
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            gap: 10px;
            animation: slideInRight 0.3s ease;
        }

        .user-bubble {
            background: linear-gradient(135deg, var(--primary-teal), #1e7a6e);
            padding: 10px 16px;
            border-radius: 20px;
            border-top-right-radius: 5px;
            color: white;
            font-size: 0.85rem;
            line-height: 1.5;
            max-width: 85%;
            word-wrap: break-word;
            white-space: pre-wrap;
            box-shadow: var(--shadow-sm);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--primary-coral), #cf5a3c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: white;
            flex-shrink: 0;
        }

        /* Typing Indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            background: white;
            border-radius: 22px;
            width: fit-content;
            box-shadow: var(--shadow-sm);
        }

        .typing-indicator span {
            width: 7px;
            height: 7px;
            background: var(--primary-teal);
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

        /* Input Area */
        .input-area {
            padding: 14px 18px;
            background: white;
            border-top: 1px solid #eef2f6;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .input-area input {
            flex: 1;
            padding: 10px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            font-size: 0.85rem;
            font-family: inherit;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .input-area input:focus {
            outline: none;
            border-color: var(--primary-teal);
            background: white;
            box-shadow: 0 0 0 3px rgba(42,157,143,0.1);
        }

        .input-area button {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-coral), #cf5a3c);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-area button:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(231,111,81,0.3);
        }

        /* Suggestions */
        .suggestions {
            padding: 10px 18px 14px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            background: white;
            border-top: 1px solid #f0f2f5;
        }

        .suggestion-chip {
            background: #f0f7f5;
            border: 1px solid #cce6e1;
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--primary-teal);
            cursor: pointer;
            transition: all 0.2s;
        }

        .suggestion-chip:hover {
            background: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
            transform: translateY(-1px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-15px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(15px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-5px); opacity: 1; }
        }

        @media (max-width: 600px) {
            .chatbot-card { height: 95vh; border-radius: 20px; }
            .bot-msg, .user-msg { max-width: 95%; }
            .bot-bubble, .user-bubble { padding: 9px 13px; font-size: 0.8rem; }
            .suggestion-chip { padding: 5px 11px; font-size: 0.65rem; }
        }
    </style>
</head>
<body>
    <div class="chatbot-container">
        <div class="chatbot-card">
            <div class="chatbot-card-header">
                <div class="header-left">
                    <div class="bot-avatar"><i class="fas fa-robot"></i></div>
                    <div class="header-info">
                        <h2>TripZone AI Assistant</h2>
                        <p><span class="online-dot"></span><span>Online • Ready to help</span></p>
                    </div>
                </div>
                <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <div class="messages-area" id="messagesArea">
                <!-- Welcome Message - কম্প্যাক্ট ভার্সন -->
                <div class="bot-msg">
                    <div class="bot-avatar-small"><i class="fas fa-robot"></i></div>
                    <div class="bot-bubble" style="padding: 12px 16px;">
                        <div style="margin-bottom: 5px;">👋 Hello <strong><?php echo htmlspecialchars($user_name); ?></strong>!</div>
                        <div style="margin-bottom: 8px;">✨ Welcome to <strong style="color:#2A9D8F;">TripZone AI Assistant</strong></div>
                        
                        <div style="height: 1px; background: #e2e8f0; margin: 6px 0;"></div>
                        
                        <div style="font-weight: 600; margin-bottom: 5px;">💡 I can help you with:</div>
                        <div style="margin-bottom: 6px;">
                            <div>• 📦 <strong>Tour packages & prices</strong></div>
                            <div>• 📅 <strong>Best time to visit</strong></div>
                            <div>• 🏖️ <strong>Destination information</strong></div>
                            <div>• 📝 <strong>Save & manage notes</strong> (CRUD)</div>
                            <div>• 🔐 <strong>Registration & login help</strong></div>
                        </div>
                        
                        <div style="height: 1px; background: #e2e8f0; margin: 6px 0;"></div>
                        
                        <div style="background: #fef9f0; border-radius: 8px; padding: 6px 10px; border-left: 3px solid #E76F51;">
                            <div style="font-weight: 600;">💬 What would you like to know?</div>
                            <div style="font-size: 0.65rem; color: #5A6E66; margin-top: 2px;">Try "package prices", "Saint Martin", or "save note"</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-area">
                <input type="text" id="messageInput" placeholder="Type your message here..." autocomplete="off">
                <button id="sendBtn"><i class="fas fa-paper-plane"></i></button>
            </div>

            <div class="suggestions">
                <button class="suggestion-chip">💰 Package Prices</button>
                <button class="suggestion-chip">🏝️ Saint Martin</button>
                <button class="suggestion-chip">🏖️ Cox's Bazar</button>
                <button class="suggestion-chip">⛰️ Sajek Valley</button>
                <button class="suggestion-chip">📝 Save a note</button>
                <button class="suggestion-chip">📋 My Notes</button>
                <button class="suggestion-chip">❓ Help</button>
            </div>
        </div>
    </div>

    <script>
        const userId = <?php echo $user_id; ?>;
        const userName = '<?php echo addslashes($user_name); ?>';
        
        const messagesArea = document.getElementById('messagesArea');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatMessage(text) {
            return text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        }

        function addMessage(text, isBot = true) {
            const div = document.createElement('div');
            div.className = isBot ? 'bot-msg' : 'user-msg';
            
            if (isBot) {
                div.innerHTML = `
                    <div class="bot-avatar-small"><i class="fas fa-robot"></i></div>
                    <div class="bot-bubble">${formatMessage(text)}</div>
                `;
            } else {
                div.innerHTML = `
                    <div class="user-bubble">${escapeHtml(text)}</div>
                    <div class="user-avatar"><i class="fas fa-user"></i></div>
                `;
            }
            messagesArea.appendChild(div);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'bot-msg';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="bot-avatar-small"><i class="fas fa-robot"></i></div>
                <div class="typing-indicator"><span></span><span></span><span></span></div>
            `;
            messagesArea.appendChild(typingDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function hideTyping() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }

        // ============ CRUD API CALLS ============

        async function saveNote(note) {
            try {
                const response = await fetch('backend/api/chatbot_save_note.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ note: note, user_id: userId })
                });
                return await response.json();
            } catch(e) { return { success: false }; }
        }

        async function getNotes() {
            try {
                const response = await fetch('backend/api/chatbot_get_notes.php?user_id=' + userId);
                return await response.json();
            } catch(e) { return { notes: [] }; }
        }

        async function deleteNote(noteId) {
            try {
                const response = await fetch('backend/api/chatbot_delete_note.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ note_id: noteId, user_id: userId })
                });
                return await response.json();
            } catch(e) { return { success: false }; }
        }

        async function updateNote(noteId, newContent) {
            try {
                const response = await fetch('backend/api/chatbot_update_note.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ note_id: noteId, content: newContent, user_id: userId })
                });
                return await response.json();
            } catch(e) { return { success: false }; }
        }

        // ============ RESPONSE GENERATOR ============

        async function getResponse(userMessage) {
            const msg = userMessage.toLowerCase();
            
            // CREATE - Save Note
            if (msg.includes('save note') || msg.includes('save a note') || msg.startsWith('note:')) {
                let note = userMessage.replace(/save a note|save note|note:/gi, '').trim();
                if (note.length > 0) {
                    const result = await saveNote(note);
                    if (result.success) {
                        return `✅ **Note saved successfully!**\n\n📝 "${note}"\n\nType **"my notes"** to see all your saved notes.`;
                    }
                    return `❌ Failed to save note. Please try again.`;
                }
                return `📝 **What would you like to save?**\n\nExample: "save note: I want to book a hotel"`;
            }
            
            // READ - Get Notes
            if (msg.includes('my notes') || msg.includes('show notes') || msg.includes('all notes')) {
                const result = await getNotes();
                if (result.success && result.notes && result.notes.length > 0) {
                    let notesList = "📋 **Your Saved Notes**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    result.notes.forEach((note, index) => {
                        notesList += `${index + 1}. 📝 ${note.content}\n   📅 ${new Date(note.created_at).toLocaleString()}\n   🆔 ID: #${note.id}\n\n`;
                    });
                    notesList += "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n💡 Commands:\n• `update note #1 new content`\n• `delete note #1`";
                    return notesList;
                }
                return `📭 **No notes found!**\n\nYou can save notes by typing:\n"save note: I want to visit Saint Martin"`;
            }
            
            // UPDATE - Edit Note
            if (msg.includes('update note') || msg.includes('edit note')) {
                const match = userMessage.match(/#(\d+)/);
                const newContent = userMessage.replace(/update note|edit note|#\d+/gi, '').trim();
                if (match && newContent) {
                    const noteId = match[1];
                    const result = await updateNote(noteId, newContent);
                    if (result.success) {
                        return `✏️ **Note updated successfully!**\n\nYour note has been updated to:\n"${newContent}"`;
                    }
                    return `❌ Note not found. Please check the ID.`;
                }
                return `📝 **To update a note:**\n\nExample: "update note #1 I want to visit Sajek Valley"`;
            }
            
            // DELETE - Remove Note
            if (msg.includes('delete note') || msg.includes('remove note')) {
                const match = userMessage.match(/#(\d+)/);
                if (match) {
                    const noteId = match[1];
                    const result = await deleteNote(noteId);
                    if (result.success) {
                        return `🗑️ **Note deleted successfully!**\n\nNote ID #${noteId} has been removed.`;
                    }
                    return `❌ Note not found or already deleted.`;
                }
                return `📝 **Which note do you want to delete?**\n\nExample: "delete note #1" (use the ID from "my notes")`;
            }
            
            // Package Prices
            if (msg.includes('package') || msg.includes('price') || msg.includes('cost')) {
                return `💰 **Tour Package Prices (BDT)**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n🏖️ **Cox's Bazar Tour**\n• 2 days / 1 night • 7,500 BDT\n\n⛰️ **Sajek Valley Tour**\n• 2 days / 1 night • 7,500 BDT\n\n🏝️ **Saint Martin Tour**\n• 4 days / 3 nights • 10,000 BDT\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n🎉 Group Discount: 10% off for 4+ people!`;
            }
            
            // Saint Martin
            if (msg.includes('saint martin') || msg.includes('st martin')) {
                return `🏝️ **Saint Martin - The Coral Island**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n📍 Bay of Bengal\n📅 4 days / 3 nights\n💰 10,000 BDT\n\n✨ **Includes:**\n✓ Beachfront Resort\n✓ Luxury Cruise Transfer\n✓ Expert Guide\n✓ All Meals\n✓ Snorkeling Gear\n\n🌟 Best for snorkeling & coral reefs!`;
            }
            
            // Cox's Bazar
            if (msg.includes('cox')) {
                return `🏖️ **Cox's Bazar - World's Longest Sea Beach**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n📏 120 km unbroken beach\n📅 2 days / 1 night\n💰 7,500 BDT\n\n✨ **Includes:**\n✓ Beachfront Hotel\n✓ AC Transport\n✓ Professional Guide\n✓ Breakfast & Lunch\n\n🌟 Best for beach lovers & sunset views!`;
            }
            
            // Sajek Valley
            if (msg.includes('sajek')) {
                return `⛰️ **Sajek Valley - Queen of Hills**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n📍 Rangamati Hill District\n📅 2 days / 1 night\n💰 7,500 BDT\n\n✨ **Includes:**\n✓ Premium Resort Stay\n✓ Jeep Transport\n✓ Local Guide\n✓ Breakfast & Dinner\n\n🌟 Best for nature lovers & cloud views!`;
            }
            
            // Best Time
            if (msg.includes('best time') || msg.includes('season')) {
                return `📅 **Best Time to Visit Bangladesh**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n🍂 **November to February (Winter)**\n→ Perfect weather! 🌤️ 20-25°C\n\n🌸 **March to April (Spring)**\n→ Pleasant and colorful 🌺\n\n⚠️ **Avoid:** June to September (Rainy season)`;
            }
            
            // Help
            if (msg.includes('help') || msg.includes('what can you do')) {
                return `🤖 **Help Guide**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n📝 **Notes (CRUD):**\n• "save note: your text"\n• "my notes"\n• "update note #1 new text"\n• "delete note #1"\n\n🏖️ **Travel Info:**\n• "package prices"\n• "Cox's Bazar"\n• "Sajek Valley"\n• "Saint Martin"\n• "best time"\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n💬 Type your command above!`;
            }
            
            // Default
            return `🌊 **Thanks for your message!**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\nI can help you with:\n\n📝 **CRUD Operations (Notes)**\n📦 **Tour packages & prices**\n📅 **Best time to visit**\n🏖️ **Destination information**\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n💡 Type **"help"** to see all commands!`;
        }

        // Send message
        async function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;
            
            addMessage(message, false);
            messageInput.value = '';
            
            showTyping();
            
            setTimeout(async () => {
                hideTyping();
                const response = await getResponse(message);
                addMessage(response, true);
            }, 500);
        }

        // Event listeners
        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
        
        // Suggestion chips
        document.querySelectorAll('.suggestion-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                messageInput.value = chip.innerText;
                sendMessage();
            });
        });
        
        console.log('✅ Chatbot loaded! User: ' + userName);
    </script>
</body>
</html>