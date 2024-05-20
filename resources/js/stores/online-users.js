import { defineStore } from 'pinia'

export const useOnlineUsersStore = defineStore('onlineUsers', {
    state: () => ({ onlineUsers: [] }),
    getters: {
        getOnlineUsers: ({ onlineUsers }) => onlineUsers,
    },
    actions: {
        setOnlineUsers(users) {
            this.onlineUsers = users
        },
        addOnlineUser(user) {
            this.onlineUsers.push(user)
        },
        removeOnlineUser(user) {
            // console
            this.onlineUsers = this.onlineUsers.filter(onlineUser => onlineUser.id !== user.id)
        },
    },
})
