<!-- public/includes/sathi_widget.php -->
<style>
    /* Floating Button */
    .sathi-float-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 9999;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .sathi-float-btn:hover {
        transform: scale(1.1);
    }
    .sathi-icon {
        font-size: 30px;
        color: white;
    }

    /* Chat Window */
    .sathi-chat-window {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        height: 500px;
        background: rgba(20, 20, 25, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        display: none; /* Hidden by default */
        flex-direction: column;
        z-index: 9999;
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .sathi-header {
        background: linear-gradient(90deg, #6366f1, #a855f7);
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .sathi-title {
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sathi-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
    }
    .sathi-close:hover { opacity: 1; }

    /* Body */
    .sathi-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.2) transparent;
    }
    .sathi-body::-webkit-scrollbar {
        width: 6px;
    }
    .sathi-body::-webkit-scrollbar-thumb {
        background-color: rgba(255,255,255,0.2);
        border-radius: 3px;
    }

    /* Messages */
    .sathi-msg {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 15px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
    }
    .sathi-msg.bot {
        background: rgba(255,255,255,0.1);
        color: #e2e8f0;
        border-bottom-left-radius: 2px;
        align-self: flex-start;
    }
    .sathi-msg.user {
        background: #6366f1;
        color: white;
        border-bottom-right-radius: 2px;
        align-self: flex-end;
    }
    .sathi-typing {
        font-size: 0.8rem;
        color: #94a3b8;
        font-style: italic;
        margin-left: 10px;
        display: none;
    }

    /* Footer / Input */
    .sathi-footer {
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
        display: flex;
        gap: 10px;
        background: rgba(0,0,0,0.2);
    }
    .sathi-input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 10px 15px;
        color: white;
        outline: none;
    }
    .sathi-input:focus {
        border-color: #6366f1;
    }
    .sathi-send-btn {
        background: #6366f1;
        border: none;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .sathi-send-btn:hover {
        background: #4f46e5;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        .sathi-chat-window {
            bottom: 0;
            right: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            border-radius: 0;
        }
        .sathi-float-btn {
            bottom: 20px;
            right: 20px;
        }
    }
</style>

<!-- Floating Button -->
<div class="sathi-float-btn" id="sathiBtn" onclick="toggleSathi()">
    <span class="sathi-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"></path><rect x="4" y="8" width="16" height="12" rx="2"></rect><path d="M2 14h2"></path><path d="M20 14h2"></path><path d="M15 13v2"></path><path d="M9 13v2"></path></svg>
    </span>
</div>

<!-- Chat Window -->
<div class="sathi-chat-window" id="sathiWindow">
    <div class="sathi-header">
        <div class="sathi-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="12" rx="2"></rect><path d="M2 14h2"></path><path d="M20 14h2"></path><path d="M15 13v2"></path><path d="M9 13v2"></path></svg>
            <div>
                <div style="font-size:1rem;">Sathi</div>
                <div style="font-size:0.7rem; opacity:0.8;">Organization Assistant</div>
            </div>
        </div>
        <button class="sathi-close" onclick="toggleSathi()">×</button>
    </div>
    
    <div class="sathi-body" id="sathiBody">
        <div class="sathi-msg bot">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; vertical-align:middle; margin-right:5px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Namaste! I am Sathi. Ask me anything about ZeroWaste-ZeroHunger!
        </div>
    </div>
    <div class="sathi-typing" id="sathiTyping">Sathi is typing...</div>

    <form class="sathi-footer" onsubmit="sendSathiMsg(event)">
        <input type="text" id="sathiInput" class="sathi-input" placeholder="Ask about our vision, contact..." autocomplete="off">
        <button type="submit" class="sathi-send-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </form>
</div>

<script>
    let sathiOpen = false;
    let sathiHistory = [];

    function toggleSathi() {
        sathiOpen = !sathiOpen;
        const info = document.getElementById('sathiWindow');
        const btn = document.getElementById('sathiBtn');
        
        if (sathiOpen) {
            info.style.display = 'flex';
            btn.style.display = 'none';
        } else {
            info.style.display = 'none';
            btn.style.display = 'flex';
        }
    }

    async function sendSathiMsg(e) {
        e.preventDefault();
        const input = document.getElementById('sathiInput');
        const msgText = input.value.trim();
        if (!msgText) return;

        // Add User Message
        appendMsg(msgText, 'user');
        input.value = '';
        
        // Show Typing
        const typingIndicator = document.getElementById('sathiTyping');
        typingIndicator.style.display = 'block';
        
        // Scroll to bottom
        const body = document.getElementById('sathiBody');
        body.scrollTop = body.scrollHeight;

        try {
            // Prepare History for context (Last 6 messages max)
            const contextLimit = 6;
            const context = sathiHistory.slice(-contextLimit);

            const response = await fetch('api/sathi_bot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: msgText, history: context })
            });
            
            const data = await response.json();
            
            typingIndicator.style.display = 'none';
            
            if (data.reply) {
                appendMsg(data.reply, 'bot');
            } else if (data.error) {
                appendMsg('<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; vertical-align:middle; margin-right:5px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ' + data.error, 'bot');
            }
        } catch (err) {
            typingIndicator.style.display = 'none';
            appendMsg('<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; vertical-align:middle; margin-right:5px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> Error connecting to Sathi.', 'bot');
            console.error(err);
        }
    }

    function appendMsg(text, sender) {
        const body = document.getElementById('sathiBody');
        const div = document.createElement('div');
        div.classList.add('sathi-msg', sender);
        div.innerHTML = text.replace(/\n/g, '<br>'); // Simple newline handling
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;

        // Add to local history
        sathiHistory.push({ role: sender === 'user' ? 'user' : 'assistant', content: text });
    }
</script>
