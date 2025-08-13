<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Start from './Start.vue';

const username = ref('');
const messages = ref([]);
const messageText = ref('');
const isUsernameSet = ref(false);

const setUsername = () => {
  const trimmedName = username.value.trim();
  if (trimmedName !== '') {
    isUsernameSet.value = true;
    localStorage.setItem('username', trimmedName); 
  }
};


const sendMessage = async () => {
  if (messageText.value.trim() === '' || !isUsernameSet.value) return;

  try {
    const payload = {
      user: username.value,
      message: messageText.value,
    };

    await axios.post('/api/messages', payload);
    
    messageText.value = '';
  } catch (error) {
    console.error('Error sending message:', error);
  }
};

console.log('User - Welcome:', username.value);

onMounted(() => {
  window.Echo.channel('chat')
    .listen('.MessageSent', (event) => {
      messages.value.push({
        user: event.message.user,
        message: event.message.message,
      });
    });
});

onUnmounted(() => {
  window.Echo.leave('chat');
});
</script>

<template>
  <div class="chat-container">
    <div v-if="!isUsernameSet" class="username-container">
      <h2>Digite seu nome de usuário</h2>
      <input v-model="username" @keyup.enter="setUsername" placeholder="Seu nome..." />
      <button @click="setUsername" class="animated-button">Entrar no Chat</button>
    </div>

    <div v-else>
      <Start :username="username" />
    </div>
  </div>
</template>

<style scoped>
.chat-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: linear-gradient(135deg, #25d366, #075E54);
  font-family: 'Arial', sans-serif;
}

.username-container {
  text-align: center;
  background-color: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
}

h2 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 20px;
}

input {
  width: 95%;
  padding: 12px;
  font-size: 1rem;
  border: 2px solid #ccc;
  border-radius: 6px;
  margin-bottom: 15px;
  outline: none;
  transition: border-color 0.3s;
}

input:focus {
  border-color: #075E54;
}

.animated-button {
  padding: 12px 20px;
  font-size: 1rem;
  border: none;
  background-color: #075E54;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  transition: transform 0.3s, background-color 0.3s, box-shadow 0.3s;
}

.animated-button:hover {
  background-color: #0db94c;
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.animated-button:active {
  background-color: #28e06b;
  transform: scale(0.95);
  box-shadow: none;
}

input:focus {
  border-color: #007bff;
}

</style>
