<template>
  <div>
    <h2>Dodaj nową wiadomość</h2>
    <router-link to="/">Lista wiadomości</router-link>
    <br><br>
    <input v-model="newMessage" placeholder="Treść wiadomości"/>
    <button @click="addMessage">Dodaj wiadomość</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      newMessage: '',
    };
  },
  methods: {
    addMessage() {
      const messageData = {message: this.newMessage};

      axios.post('/api', messageData)
          .then(response => {
            alert('Dodano wiadomość');

            this.newMessage = '';
          })
          .catch(error => {
            console.error('Błąd podczas dodawania wiadomości:', error);
          });
    },
  },
};
</script>
