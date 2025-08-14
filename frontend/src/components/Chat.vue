<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { defineProps, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  name: String,
});

const username = ref('')
const messages = ref([])
const messageText = ref('')
const name = ref(props.name)

const sendMessage = async () => {
  if (!username.value.trim() || !messageText.value.trim()) return

  localStorage.setItem('username', username.value.trim())

  try {
    const payload = {
      user: username.value,
      message: messageText.value,
    }
    await axios.post('http://localhost:8000/api/messages', payload)
    messageText.value = ''
  } catch (error) {
    console.error('Error sending message:', error)
  }
}

watch(() => props.name, (newVal) => {
  name.value = newVal
})

onMounted(() => {
  const savedName = localStorage.getItem('username')
  if (savedName) {
    username.value = savedName
  }

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
  <div class="chat-wrapper">
    <div class="chat-header">
      <img src="/if.png" alt="Avatar" class="header-avatar" />
      Grupo Geral - IF
    </div>

    <div class="chat-box">
      <div
        v-for="(msg, index) in messages"
        :key="index"
        class="message"
        :class="msg.user === username ? 'sent' : 'received'"
      >
        <strong>{{ msg.user }}:</strong> {{ msg.message }}
        <div class="message-time">{{ msg.time }}</div>
      </div>
    </div>

    <div class="chat-input">
      <input
        v-if="!username"
        v-model="username"
        class="name-input"
        placeholder="Seu nome"
      />
      <input
        v-model="messageText"
        @keyup.enter="sendMessage"
        class="msg-input"
        placeholder="Digite uma mensagem..."
      />
      <button @click="sendMessage" class="send-btn">Enviar</button>
    </div>
  </div>
</template>

<style scoped>
.chat-wrapper {
  width: 85rem;
  height: 100vh;
  margin: 0 auto;
  margin-left: -1rem;
  font-family: sans-serif;
  background: #e5ddd5;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);

  display: flex;
  flex-direction: column;
}

.header-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
  vertical-align: middle;
}

.chat-header {
  background: #075e54;
  color: white;
  padding: 15px;
  font-size: 1.2rem;
}

.chat-box {
  flex: 1;
  overflow-y: auto;
  padding: 15px;
  display: flex;
  flex-direction: column;
}

.message {
  max-width: 70%;
  padding: 8px 12px;
  margin-bottom: 10px;
  border-radius: 7px;
  word-wrap: break-word;
  font-size: 0.95rem;
}

.sent {
  background: #dcf8c6;
  align-self: flex-end;
}

.received {
  background: white;
  align-self: flex-start;
}

.message-time {
  font-size: 11px;
  color: #666;
  margin-top: 3px;
}

.chat-input {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  background: #f0f0f0;
  border-top: 1px solid #ddd;
}

.name-input,
.msg-input {
  padding: 10px 12px;
  border: none;
  border-radius: 10px;
  outline: none;
  font-size: 0.95rem;
  background: white;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
  transition: box-shadow 0.2s ease;
}

.name-input {
  text-align: center;
  width: 100px;
  flex: 0 0 130px;
}

.name-input:focus,
.msg-input:focus {
  box-shadow: 0 0 0 2px rgba(37,211,102,0.3);
}

.msg-input {
  flex: 1;
}

.send-btn {
  background: #25d366;
  color: white;
  border: none;
  padding: 10px 16px;
  cursor: pointer;
  border-radius: 10px;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease, transform 0.1s ease;
}

.send-btn:hover {
  background: #1ebe5d;
  transform: scale(1.05);
}

.send-btn:active {
  transform: scale(0.95);
}
</style>
