import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        dialogFormVisible: false 
    },
    mutations: {
        handleDialog (state, visible) {
            state.dialogFormVisible = visible;
        }
    }
})
