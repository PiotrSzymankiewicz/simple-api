<template>
  <div class="message-detail">
    <div class="message-content" v-if="message">
      <h3>Szczegóły JSON</h3>
      <p>{{ viewMessage }}</p>
      <button @click="closeDetail">Zamknij</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ["message"],
  data() {
    return {
      viewMessage: {},
    };
  },
  mounted() {
    this.getDetails();
  },
  methods: {
    getDetails() {
      const uuid = this.message.toString();

      axios.get(`/api/${uuid}`)
          .then((response) => {
            this.viewMessage = JSON.stringify(response.data, null, 2);
          });
    },
    closeDetail() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.message-detail {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.message-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  max-width: 80%;
  text-align: center;
}

button {
  margin-top: 10px;
}
</style>
