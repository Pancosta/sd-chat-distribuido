<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
const chats = [
  {
    name: "Grupo Geral - IF",
    avatar: "/if.png",
    status: "online",
    lastMessage: "...",
    time: "03:42",
    unread: 4
  },
];

const username = ref('')
const messages = ref([])
const messageText = ref('')

onMounted(() => {
  window.Echo.channel('chat').listen('.MessageSent', (event) => {
    messages.value.push({
      user: event.message.user,
      message: event.message.message,
      time: new Date().toLocaleTimeString(), 
    })
  })
})

onUnmounted(() => {
  window.Echo.leave('chat')
})
</script>

<template>
  <div class="sidebar">
    <h2 class="title">Chat Distribuido</h2>
    <div class="section-header">
      <span>Mensagens Recentes</span>
      <span class="count">{{ chats.length }}</span>
    </div>
    <div class="menu">
        <div class="item">
            <p>Contatos</p>
        </div>
        <div class="item">
            <p>Status</p>
        </div>
        <div class="item">
            <p>Chamadas</p>
        </div>
    </div>

    <div class="chat-list">
      <div v-for="(chat, index) in chats" :key="index" class="chat-item">
        <img :src="chat.avatar" alt="Avatar" class="avatar" />
        <div class="chat-info">
        <div class="chat-header">
          <span class="chat-name">{{ chat.name }}</span>
          <span v-if="messages.length > 0" class="chat-time">{{  messages[messages.length - 1].time }}</span>
          <span v-else class="chat-time">{{ chat.time }}</span>
        </div>
        <div class="chat-message">
          <p>
            <strong v-if="messages.length > 0">{{ messages[messages.length - 1].user }}: </strong>
            <span v-if="messages.length > 0">{{ messages[messages.length - 1].message }}</span>
            <span v-else class="no-messages">....</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<style scoped>
.sidebar {
  width: 34rem;
  height: 100vh;
  background: #075E54;
  border: solid 1px #ccc;
  color: white;
  padding: 10px;
  font-family: sans-serif;
}

.menu {
  display: flex;
  justify-content: space-between;
  padding: 0 15px;
}

.item {
   border-radius: 20px;
   padding: 10px;
   height: 1.5rem;
   width: 7rem;
   margin: 5px 0;
   display: inline-flex;
   justify-content: center;
   align-items: center;
   border: solid 1px #25d366;
   font-weight: bold;
}

.title {
  font-size: 1.2rem;
  margin-bottom: 5px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 10px;    
}

.title, .section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: bold;
  margin-left: 1rem;
}

.count {
  background: #333;
  border-radius: 10px;
  padding: 2px 8px;
}

.chat-list {
  padding: 1cap;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.chat-item {
  display: flex;
  align-items: center;
  background: #4b4b4b;
  border: solid 1px #25d366;
  padding: 8px;
  border-radius: 8px;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.chat-info {
  flex-grow: 1;
  height: 4rem;
  display: flex;
  flex-direction: column;
  justify-content: center; 
}

.chat-header {
  display: flex;
  justify-content: space-between;
  font-weight: bold;
}

.chat-name {
  color: white;
}

.chat-time {
  font-size: 0.75rem;
  color: #aaa;
}

.chat-message {
  margin-top: 5px;
  font-size: 0.85rem;
  color: #ccc;
}

.chat-message p {
  margin: 0;
}

.chat-meta {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-bottom: 4px;
}

.status-dot.online {
  background: #25d366;
}

.status-dot.away {
  background: #f1c40f;
}

.status-dot.offline {
  background: #7f8c8d;
}

.unread-badge {
  background: #25d366;
  color: white;
  font-size: 0.7rem;
  padding: 2px 6px;
  border-radius: 10px;
}
</style>
