<template>
  <div>
    <h1>Lista Wiadomości</h1>
    <p>
      <router-link to="/add-message">Dodaj wiadomość</router-link>
    </p>
    <div>
      <label for="sortBy">Sortuj według:</label>
      <select id="sortBy" v-model="sortBy" @change="updateRoute">
        <option value="date">Data utworzenia</option>
        <option value="uuid">UUID</option>
      </select>
      <label for="sortOrder">Kolejność:</label>
      <select id="sortOrder" v-model="sortOrder" @change="updateRoute">
        <option value="asc">Rosnąco</option>
        <option value="desc">Malejąco</option>
      </select>
    </div>
    <br>
    <dx-data-grid
        :data-source="messages"
        :keyExpr="'uuid'"
        :show-borders="true"
        :column-auto-width="true"
        :allow-column-reordering="true"
        :allow-column-resizing="true"
        :show-row-lines="true"
        :show-column-lines="true"
    >
      <DxColumn caption="Plik" data-field="uuid"/>
      <DxColumn caption="Data utworzenia" data-field="createAt"/>
      <DxColumn caption="Akcje" type="buttons">
        <DxButton>
          <template #default="data">
            <button @click="showDetail(data.data.row.key)">Podgląd wiadomości</button>
          </template>
        </DxButton>
      </DxColumn>
    </dx-data-grid>
    <message-detail :message="selectedUuid" v-if="selectedUuid !== null" @close="closeDetail"/>
  </div>
</template>

<script>
import axios from 'axios';
import DxDataGrid, {DxColumn, DxButton, DxPager} from 'devextreme-vue/data-grid';
import MessageDetailsModal from "./MessageDetailsModal.vue";

export default {
  components: {
    DxDataGrid,
    DxColumn,
    DxButton,
    DxPager,
    'message-detail': MessageDetailsModal,
  },
  data() {
    return {
      messages: [],
      sortBy: 'date',
      sortOrder: 'asc',
      selectedUuid: null,
      indexClicked: null,
    };
  },
  created() {
    this.fetchMessages();
  },
  methods: {
    showDetail(uuid) {
      this.selectedUuid = uuid;
    },
    closeDetail() {
      this.selectedUuid = null;
    },
    fetchMessages() {
      const sortBy = this.sortBy;
      const sortOrder = this.sortOrder;

      axios.get(`/api?sortBy=${sortBy}&sortOrder=${sortOrder}`)
          .then(response => {
            this.messages = response.data;
          })
          .catch(error => {
            console.error(error);
          });
    },
    updateRoute() {
      this.$router.push({
        path: '/',
        query: {
          sortBy: this.sortBy,
          sortOrder: this.sortOrder,
        },
      });
    },
  },
  watch: {
    $route(to, from) {
      if (to.query.sortBy !== from.query.sortBy || to.query.sortOrder !== from.query.sortOrder) {
        this.sortBy = to.query.sortBy || 'date';
        this.sortOrder = to.query.sortOrder || 'asc';
        this.fetchMessages();
      }
    }
  }
};
</script>
