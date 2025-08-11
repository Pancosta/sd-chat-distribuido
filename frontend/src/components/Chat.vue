<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const username = ref('');
const messages = ref([]);
const messageText = ref('');
const isUsernameSet = ref(false);

const setUsername = () => {
  if (username.value.trim() !== '') {
    isUsernameSet.value = true;
  }
};

const sendMessage = async () => {
  if (messageText.value.trim() === '' || !isUsernameSet.value) return;

  try {
    const payload = {
      user: username.value,
      message: messageText.value,
    };
    await axios.post('http://localhost:8000/api/messages', payload);

    // A linha abaixo foi comentada para corrigir a duplicidade.
    // A mensagem agora só é adicionada quando volta do servidor via WebSocket.
    // messages.value.push(payload);
    
    messageText.value = '';
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

onMounted(() => {
  window.Echo.channel('chat')
    .listen('.MessageSent', (event) => {
      messages.value.push({
        user: event.message.user,
        message: event.message.message,
      });
    });
});
</script>

<template>
  <div class="chat-container">
    <div v-if="!isUsernameSet" class="username-container">
      <h2>Digite seu nome de usuário</h2>
      <input v-model="username" @keyup.enter="setUsername" placeholder="Seu nome..." />
      <button @click="setUsername">Entrar no Chat</button>
    </div>

    <div v-else>
      <div class="messages-area">
        <div v-for="(msg, index) in messages" :key="index" class="message" :class="{ 'my-message': msg.user === username }">
          <strong>{{ msg.user }}:</strong> {{ msg.message }}
        </div>
      </div>
      <div class="input-area">
        <input v-model="messageText" @keyup.enter="sendMessage" placeholder="Digite uma mensagem..." />
        <button @click="sendMessage">Enviar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.chat-container {
  max-width: 600px;
  margin: 0 auto;
  border: 1px solid #ccc;
  padding: 20px;
  border-radius: 8px;
}
.username-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.messages-area {
  height: 400px;
  overflow-y: auto;
  border: 1px solid #eee;
  padding: 10px;
  margin-bottom: 10px;
  text-align: left;
  display: flex;
  flex-direction: column;
}
.message {
  padding: 5px 10px;
  border-radius: 10px;
  margin-bottom: 8px;
  max-width: 70%;
  word-wrap: break-word;
  background-color: #f1f0f0;
  align-self: flex-start;
}
.my-message {
  align-self: flex-end;
  background-color: #007bff;
  color: white;
}
.input-area {
  display: flex;
}
input {
  flex-grow: 1;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
button {
  padding: 8px 12px;
  border: none;
  background-color: #007bff;
  color: white;
  border-radius: 4px;
  margin-left: 10px;
  cursor: pointer;
}
button:hover {
  background-color: #0056b3;
}
</style>