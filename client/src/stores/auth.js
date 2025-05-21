import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: {},
        token: null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
    actions: {
        setToken(token) {
            this.token = token
            localStorage.setItem('token', token)
        },
        setUser(user) {
            this.user = user
        },
        removeToken() {
            this.token = null
            localStorage.removeItem('token')
        },
        removeUser() {
            this.user = {}
        },
        updateUser(user) {
            this.user = user
        }
    },
    persist: {
        paths: ['user'],
    }
})
