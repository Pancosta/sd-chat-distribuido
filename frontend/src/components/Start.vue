<script setup>
import { defineProps } from 'vue';
import Chat from './Chat.vue';
import User from './User.vue';

const props = defineProps({
  username: String,  
});

console.log('User Start:', props.username);

</script>

<template>
    <div class="chat-container">
        <User />
        <Chat :name="props.username" />
    </div>
</template>

<style scoped>
.chat-container {
  display: flex;          
  flex-direction: row;    
  gap: 16px;               
}
</style>