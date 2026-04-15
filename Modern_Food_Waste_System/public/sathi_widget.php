<!-- public/sathi_widget.php -->
<div id="sathi-widget">
    <!-- Chat Button -->
    <button id="sathi-toggle" aria-label="Chat with Sathi" style="
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary);
        border: none;
        box-shadow: 0 5px 20px rgba(0, 255, 136, 0.4);
        cursor: pointer;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    ">
        <span style="font-size: 30px;">🤖</span>
    </button>

    <!-- Chat Window -->
    <div id="sathi-window" style="
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        height: 500px;
        background: var(--bg-panel);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        display: none;
        flex-direction: column;
        z-index: 1000;
        overflow: hidden;
        backdrop-filter: blur(10px);
    ">
        <!-- Header -->
        <div style="
            background: rgba(0, 255, 136, 0.1);
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:10px; height:10px; background:var(--primary); border-radius:50%;"></div>
                <strong style="color:var(--text-main);">Sathi AI</strong>
            </div>
            <button id="sathi-close" style="background:none; border:none; color:var(--text-muted); cursor:pointer; font-size:1.2rem;">&times;</button>
        </div>

        <!-- Messages Area -->
        <div id="sathi-messages" style="
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        ">
            <!-- Welcome Message -->
            <div style="align-self: flex-start; background: rgba(255,255,255,0.05); padding: 10px 15px; border-radius: 12px 12px 12px 2px; max-width: 80%; color: var(--text-main); font-size: 0.9rem;">
                Namaste! 🙏 I am Sathi. How can I help you with ZeroWaste today?
            </div>
        </div>

        <!-- Input Area -->
        <div style="
            padding: 15px;
            border-top: 1px solid var(--glass-border);
            display: flex;
            gap: 10px;
        ">
            <input type="text" id="sathi-input" placeholder="Ask about donations..." style="
                flex: 1;
                background: var(--bg-input);
                border: 1px solid var(--glass-border);
                padding: 10px;
                border-radius: 20px;
                color: var(--text-main);
                outline: none;
            ">
            <button id="sathi-send" style="
                background: var(--primary);
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                color: black;
                font-weight: bold;
            ">➤</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleCtx = document.getElementById('sathi-toggle');
    const windowCtx = document.getElementById('sathi-window');
    const closeCtx = document.getElementById('sathi-close');
    const sendCtx = document.getElementById('sathi-send');
    const inputCtx = document.getElementById('sathi-input');
    const msgsCtx = document.getElementById('sathi-messages');

    // Toggle Visibility
    toggleCtx.addEventListener('click', () => {
        const isHidden = windowCtx.style.display === 'none';
        windowCtx.style.display = isHidden ? 'flex' : 'none';
        if(isHidden) inputCtx.focus();
    });

    closeCtx.addEventListener('click', () => {
        windowCtx.style.display = 'none';
    });

    // Send Logic
    const sendMessage = async () => {
        const text = inputCtx.value.trim();
        if(!text) return;

        // User Bubble
        appendMessage(text, 'user');
        inputCtx.value = '';

        // Helper Loading
        const loadingId = appendMessage('Thinking...', 'bot', true);

        try {
            const res = await fetch('api/sathi_bot.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            
            // Remove Loading
            document.getElementById(loadingId).remove();
            
            appendMessage(data.reply || "Sorry, I am having trouble connecting.", 'bot');
        } catch (e) {
            document.getElementById(loadingId).remove();
            appendMessage("Network error. Please try again.", 'bot');
        }
    };

    sendCtx.addEventListener('click', sendMessage);
    inputCtx.addEventListener('keypress', (e) => {
        if(e.key === 'Enter') sendMessage();
    });

    function appendMessage(text, sender, isLoading = false) {
        const div = document.createElement('div');
        const id = 'msg-' + Date.now();
        div.id = id;
        
        const isUser = sender === 'user';
        div.style.alignSelf = isUser ? 'flex-end' : 'flex-start';
        div.style.background = isUser ? 'var(--primary)' : 'rgba(255,255,255,0.05)';
        div.style.color = isUser ? 'black' : 'var(--text-main)';
        div.style.padding = '10px 15px';
        div.style.borderRadius = isUser ? '12px 12px 2px 12px' : '12px 12px 12px 2px';
        div.style.maxWidth = '80%';
        div.style.fontSize = '0.9rem';
        div.style.lineHeight = '1.4';
        
        if(isLoading) {
            div.style.fontStyle = 'italic';
            div.style.opacity = '0.7';
        }

        div.innerText = text; // Safe for text
        msgsCtx.appendChild(div);
        msgsCtx.scrollTop = msgsCtx.scrollHeight;
        return id;
    }
});
</script>
