import apiClient from "@/plugins/axios"

const chatApi = {
    getConversationByCustomer() {
        return apiClient.get('/chat/conversation')
    },
    getMessages(conversationId) {
        return apiClient.get(`/chat/${conversationId}/messages`)
    },
    sendMessage(conversationId, data) {
        return apiClient.post(`/chat/${conversationId}/messages`, data)
    },
    markAsRead(conversationId) {
        return apiClient.patch(`/chat/${conversationId}/mark-read`)
    },
    get(params = {}) {
        return apiClient.get('/chat', { params })
    },
    connect(conversationId) {
        return apiClient.patch(`/chat/${conversationId}/connect`)
    },
    getOne(id) {
        return apiClient.get(`/chat/${id}`)
    }
}

export default chatApi 