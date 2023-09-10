import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import MessageList from "./views/MessageList.vue";
import MessageDetailsModal from "./views/MessageDetailsModal.vue";
import AddMessageForm from "./views/AddMessageForm.vue";

import 'devextreme/dist/css/dx.common.css';
import 'devextreme/dist/css/dx.light.css';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'MessageList',
        component: MessageList,
    },
    {
        path: '/message/:uuid',
        name: 'MessageDetailsModal',
        component: MessageDetailsModal,
    },
    {
        path: '/add-message',
        name: 'AddMessageForm',
        component: AddMessageForm,
    },
];

const router = new VueRouter({
    routes,
    mode: 'history',
});

new Vue({
    render: (h) => h(App),
    router,
}).$mount('#app');