<template>
    <div class="admin-page">
        <div class="admin-content">
            <div class="admin-content__heading">
                <h3>Quản lý tin nhắn</h3>
            </div>
            <div class="admin-content__container">
                <div class="admin-chat-container">
                    <div class="admin-chat-header">
                        <div class="admin-chat-user-info">
                            <div class="admin-user-avatar">
                                <img :src="getImageUrl(conversation.customer?.avatar?.path, '/images/empty-avatar.webp')" alt="" />
                                <span class="admin-online-status"></span>
                            </div>
                            <div class="admin-user-details">
                                <h4>{{ conversation.customer?.name }}</h4>
                                <span class="admin-user-status">Đang hoạt động</span>
                            </div>
                        </div>
                        <div class="admin-chat-actions">
                            <button class="admin-action-btn">
                                <i class="icon-more"></i>
                            </button>
                        </div>
                    </div>

                    <div class="admin-chat-messages" ref="messagesContainer">
                        <div v-for="message in messages" :key="message.id">
                            <div class="admin-message-group" v-if="message.content">
                                <div class="admin-message" :class="{ 'sent': message.sender_type === 'staff', 'received': message.sender_type === 'customer' }">
                                    <div class="admin-message-content">
                                        <div class="admin-message-bubble">
                                            {{ message.content }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="admin-message-group" v-if="message.image_url">
                                <div class="admin-message" :class="{ 'sent': message.sender_type === 'staff', 'received': message.sender_type === 'customer' }">
                                    <div class="admin-message-content">
                                        <img :src="getImageUrl(message?.image?.path)" alt="" style="max-width: 400px;max-height: 400px;border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="admin-message-group">
                            <div class="admin-message received">
                                <div class="admin-message-avatar">
                                    <img src="https://via.placeholder.com/32" alt="User" />
                                </div>
                                <div class="admin-message-content">
                                    <div class="admin-typing-indicator">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="admin-chat-input-container">
                        <div class="admin-chat-input">
                            <button class="admin-attachment-btn" @click="$refs.fileInput.click()">
                                <i class="fa fa-image"></i>
                            </button>
                            <input ref="fileInput" type="file" accept="image/*" style="display:none;" @change="handleFileUpload">
                            <div class="admin-input-wrapper">
                                <textarea 
                                    v-model="newMessage" 
                                    placeholder="Nhập tin nhắn..." 
                                    @keydown.enter.prevent="sendMessage"
                                    @input="handleInput"
                                    rows="1"
                                    ref="messageInput"
                                ></textarea>
                            </div>
                            <!-- <button class="emoji-btn">
                                <i class="icon-emoji"></i>
                            </button> -->
                            <button class="admin-send-btn" @click="sendMessage" :disabled="!newMessage.trim()">
                                <i class="icon-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pusher from 'pusher-js';
import { getImageUrl } from '@/utils/helpers';
import { chatApi } from '@/api';

export default {
    name: 'ChatForm',
    data() {
        return {
            conversationId: this.$route.params.id,
            newMessage: '',
            messages: [], conversation: {},
            selectedFile: null,
            pusher: null,
            channel: null,
            tmpMessage: [],
        }
    },
    created() {
        this.initializePusher();
        this.subscribeToChannel(this.conversationId);
        this.fetchData();
        this.markAsRead(this.conversationId);
    },
    methods: {
        getImageUrl,
        async fetchData() {
            const req = [
                chatApi.getOne(this.conversationId),
                chatApi.getMessages(this.conversationId),
            ]
            const [res1, res2] = await this.$swal.withLoading(Promise.all(req));
            this.conversation = res1.data;
            this.messages = res2.data.data.reverse();
            this.scrollToBottom();
        },
        initializePusher() {
            this.pusher = new Pusher(process.env.VUE_APP_PUSHER_KEY, {
                cluster: process.env.VUE_APP_PUSHER_CLUSTER,
                encrypted: true,
            });
        },
        subscribeToChannel(conversationId) {
            if (this.channel) {
                this.channel.unsubscribe();
            }
            const channelName = `chat.${conversationId}`;
            this.channel = this.pusher.subscribe(channelName);
            this.channel.bind('App\\Events\\MessageSent', (data) => {
                const message = data;
                if (message.sender_type === 'staff') {
                    const f = this.tmpMessage.shift();
                    if (f) {
                        const i = this.messages.findIndex(m => m.id === f.id);
                        if (i !== -1) {
                            this.messages[i] = message;
                        } else {
                            this.messages.push(message);
                        }
                    }            
                } else {
                    this.messages.push(message);
                }
                this.scrollToBottom();
            });
        },
        async sendMessage() {
            if (!this.newMessage.trim() && !this.selectedFile) return;
            const formData = new FormData();
            if (this.newMessage.trim()) {
                formData.append('content', this.newMessage.trim());
            }
            if (this.selectedFile) {
                formData.append('image', this.selectedFile);
            }
            this.tempMessage();
            try {
                await chatApi.sendMessage(this.conversationId, formData);
            } catch (e) {
                console.error(e);
            } finally {
                this.newMessage = '';
                this.selectedFile = null;
                this.autoResize();
                this.scrollToBottom();
            }
        },
        tempMessage() {
            const tempMessage = {
                id: Date.now(),
                content: this.newMessage.trim(),
                sender_type: 'staff',
            }
            this.tmpMessage.push(tempMessage);
            this.messages.push(tempMessage);
            this.scrollToBottom();
            this.newMessage = '';
            this.selectedFile = null;
        },
        async markAsRead(id) {
            if (!id) return;
            try {
                await chatApi.markAsRead(id);
            } catch (e) {
                console.error(e);
            }
        },
        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                this.$swal.fire('Lỗi!', 'File quá lớn. Vui lòng chọn file nhỏ hơn 5MB', 'error');
                return;
            }
            this.selectedFile = file;
            await this.sendMessage();
            event.target.value = '';
        },
        handleInput() {
            this.autoResize();
        },
        autoResize() {
            const textarea = this.$refs.messageInput;
            if (textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    },
    beforeUnmount() {
        this.markAsRead(this.conversationId);
    }
}
</script>

<style>
.admin-chat-container {
    display: flex;
    flex-direction: column;
    height: 80vh;
    position: relative;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Chat Header */
.admin-chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: white;
    color: #1f2937;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.admin-chat-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-user-avatar {
    position: relative;
}

.admin-user-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
}

.admin-online-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    background: #10b981;
    border: 2px solid white;
    border-radius: 50%;
}

.admin-user-details h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.admin-user-status {
    font-size: 12px;
    opacity: 0.8;
}

.admin-chat-actions {
    display: flex;
    gap: 8px;
}

.admin-action-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #f8fafc;
    color: #6b7280;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}

.admin-action-btn:hover {
    background: #f1f5f9;
    color: #374151;
    border-color: #cbd5e1;
}

/* Messages */
.admin-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    background: white;
    display: flex;
    flex-direction: column;
}

.admin-message-group {
    margin-bottom: 16px;
}

.admin-message {
    display: flex;
    gap: 12px;
    max-width: 70%;
}

.admin-message.sent {
    margin-left: auto;
    flex-direction: row-reverse;
}

.admin-message.received {
    margin-right: auto;
}

.admin-message-avatar img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.admin-message-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.admin-message.sent .admin-message-content {
    align-items: flex-end;
}

.admin-message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
    position: relative;
}

.admin-message.received .admin-message-bubble {
    background: #f8fafc;
    color: #374151;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.admin-message.sent .admin-message-bubble {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.admin-message-time {
    font-size: 11px;
    color: #9ca3af;
    padding: 0 4px;
}

/* Typing Indicator */
.admin-typing-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 18px;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.admin-typing-indicator span {
    width: 6px;
    height: 6px;
    background: #9ca3af;
    border-radius: 50%;
    animation: typing 1.4s ease-in-out infinite;
}

.admin-typing-indicator span:nth-child(1) { animation-delay: 0s; }
.admin-typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.admin-typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30% { transform: translateY(-10px); opacity: 1; }
}

/* Chat Input */
.admin-chat-input-container {
    padding: 20px 24px;
    background: white;
    border-top: 1px solid #e2e8f0;
}

.admin-chat-input {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.admin-chat-input:focus-within {
    border-color: #1f2937;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.admin-attachment-btn, .admin-emoji-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: none;
    color: #6b7280;
    cursor: pointer;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.admin-attachment-btn:hover, .admin-emoji-btn:hover {
    background: #e5e7eb;
    color: #374151;
}

.admin-input-wrapper {
    flex: 1;
}

.admin-input-wrapper textarea {
    width: 100%;
    border: none;
    background: none;
    resize: none;
    outline: none;
    font-size: 14px;
    line-height: 1.4;
    color: #374151;
    min-height: 20px;
    max-height: 120px;
    font-family: inherit;
}

.admin-input-wrapper textarea::placeholder {
    color: #9ca3af;
}

.admin-send-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.admin-send-btn:hover:not(:disabled) {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.admin-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Icons */
.icon-phone:before { content: "📞"; }
.icon-video:before { content: "📹"; }
.icon-more:before { content: "⋯"; }
.icon-attachment:before { content: "📎"; }
.icon-emoji:before { content: "😊"; }
.icon-send:before { content: "➤"; }

/* Scrollbar */
.admin-chat-messages::-webkit-scrollbar {
    width: 6px;
}

.admin-chat-messages::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.admin-chat-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.admin-chat-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-page {
        padding: 12px;
    }
    
    .admin-admin-chat-container {
        height: calc(100vh - 120px);
    }
    
    .admin-message {
        max-width: 85%;
    }
    
    .admin-chat-header {
        padding: 16px 20px;
    }
    
    .admin-chat-messages {
        padding: 16px;
    }
    
    .admin-chat-input-container {
        padding: 16px 20px;
    }
}
</style>

