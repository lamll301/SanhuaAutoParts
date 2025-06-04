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
                    <img :src="getImageUrl(conversation?.staff?.avatar?.path, 'https://cdn-icons-png.flaticon.com/512/7556/7556840.png')" alt="" class="chat-img">
                    <span>{{ conversation?.staff_id ? conversation?.staff?.name : 'Đang chờ nhân viên...' }}</span>
                </div>
                <div class="chat-header-actions">
                    <button class="chat-header-action" @click="closeChat">×</button>
                </div>
            </div>
            <div class="chat-body" ref="chatBody">
                <template v-for="message in messages" :key="message.id">
                    <div v-if="message.image_url" class="chat-message"
                    :class="{'chat-incoming': message.sender_type === 'staff', 
                    'chat-outgoing': message.sender_type === 'customer'}">
                        <img :src="getImageUrl(message.image?.path)" alt="message.image?.filename">
                    </div>
                    <div class="chat-message" v-if="message.content"
                        :class="{'chat-incoming': message.sender_type === 'staff', 
                        'chat-outgoing': message.sender_type === 'customer'}"
                    >
                        {{ message.content }}
                    </div>
                </template>
            </div>
            <div class="chat-footer">
                <input ref="fileInput" type="file" accept="image/*" style="display:none;" @change="handleFileUpload">
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
import Pusher from 'pusher-js';
import { chatApi } from '@/api';
import { getImageUrl } from '@/utils/helpers';

export default {
    data() {
        return {
            isChatOpen: false,
            newMessage: '',
            unreadCount: 0,
            messages: [],
            pusher: null, channel: null, selectedFile: null,
            conversation: null,
        }
    },
    created() {
        this.initializePusher();
        this.getConversation();
    },
    methods: {
        getImageUrl,
        async getConversation() {
            try {
                const res = await chatApi.getConversationByCustomer();
                this.conversation = res.data;
                this.unreadCount = res.data.unread_count_by_customer;
                this.subscribeToChannel(this.conversation.id);
                await this.loadMessages();
            } catch (e) {
                console.error(e);
            }
        },
        async markAsRead() {
            try {
                await chatApi.markAsRead(this.conversation.id);
                this.unreadCount = 0;
            } catch (e) {
                console.error(e);
            }
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
                const existingMessage = this.messages.find(msg => msg.id === data.id);
                if (!existingMessage) {
                    this.messages.unshift(data);
                }
                this.scrollToBottom();
            });
        },
        async loadMessages() {
            if (!this.conversation) return;
            try {
                const response = await chatApi.getMessages(this.conversation.id);
                this.messages = response.data.data;
                this.scrollToBottom();
            } catch (e) {
                console.error(e);
            }
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
            try {
                await chatApi.sendMessage(this.conversation.id, formData);
            } catch (e) {
                console.error(e);
            } finally {
                this.newMessage = '';
                this.selectedFile = null;
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
        closeChat() {
            this.isChatOpen = false;
        },
        async openChat() {
            this.isChatOpen = true;
            await this.markAsRead();
        },
    },
}
</script>