<template>
    <button v-show="!isChatOpen" class="chat-btn" @click="openChat">
        <i class="fa-regular fa-comments"></i>
        <span>Chat</span>
        <span v-if="unreadCount" class="chat-btn-notice">{{ unreadCount }}</span>
    </button>
    <div class="chat-modal" v-show="isChatOpen">
        <div class="chat-box">
            <div class="chat-header">
                <div class="chat-header-info">
                    <img src="https://cdn-icons-png.flaticon.com/512/7556/7556840.png" alt="" class="chat-img">
                    <span>Nhân viên tư vấn</span>
                </div>
                <div class="chat-header-actions">
                    <button class="chat-header-action" @click="closeChat">×</button>
                </div>
            </div>
            <div class="chat-body" ref="chatBody">
                <div class="chat-message chat-incoming">Xin chào, bạn có vấn đề gì cần giúp không?</div>
                <div class="chat-message chat-outgoing">Tôi khỏe, cảm ơn!</div>
                <div class="chat-message chat-incoming">Xin chào, bạn có vấn đề gì cần giúp không?</div>
                <div class="chat-message chat-outgoing">Tôi khỏe, cảm ơn!</div><div class="chat-message chat-incoming">Xin chào, bạn có vấn đề gì cần giúp không?</div>
                <div class="chat-message chat-outgoing">Tôi khỏe, cảm ơn!</div><div class="chat-message chat-incoming">Xin chào, bạn có vấn đề gì cần giúp không?</div>
                <div class="chat-message chat-outgoing">Tôi khỏe, cảm ơn!</div><div class="chat-message chat-incoming">Xin chào, bạn có vấn đề gì cần giúp không?</div>
                <div class="chat-message chat-outgoing">Tôi khỏe, cảm ơn!</div>
                <div class="chat-message chat-outgoing">
                    <img src="https://picsum.photos/1000" alt="">
                </div>
                <div class="chat-message chat-incoming">
                    <img src="https://picsum.photos/200" alt="">
                </div>
            </div>
            <div class="chat-footer">
                <input ref="fileInput" type="file" accept="image/*" style="display:none;">
                <button @click="$refs.fileInput.click()" type="button" class="chat-image-btn">
                <i class="fa fa-image"></i>
                </button>
                <input v-model="newMessage" type="text" class="chat-input" placeholder="Nhập tin nhắn..." @keyup.enter="sendMessage">
                <button class="chat-send-btn" @click="sendMessage">➤</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // dùng socket
            isChatOpen: false,
            newMessage: '',
            unreadCount: 10,
            messages: [],
        }
    },
    mounted() {
        
    },
    methods: {
        closeChat() {
            this.isChatOpen = false;
        },
        openChat() {
            this.isChatOpen = true;
            this.unreadCount = 0;
        },
        sendMessage() {
            if (this.newMessage.trim()) {

                this.newMessage = '';
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatBody
                if (container) {
                    container.scrollTop = container.scrollHeight
                }
            })
        },
    },
}
</script>