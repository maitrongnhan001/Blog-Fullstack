import vue from "vue";
import vuex from 'vuex';
vue.use(vuex);

export default new vuex.Store({
    state: {
        deleteModalObj: {
            title: '',
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            isDeleted: false
        },
        user: false
    },
    getters: {
        getDeleteModalObj (state) {
            return state.deleteModalObj;
        }
    },
    mutations: {
        setDeletedModal (state, data) {
            const deleteModalObj = {
                    title: '',
                    showDeleteModal: false,
                    deleteUrl: '',
                    data: null,
                    isDeleted: data
            }
            state.deleteModalObj = deleteModalObj;
        },
        setDeletingModalObj (state, data) {
            state.deleteModalObj = data; 
        },
        updateUser (state, data) {
            state.user = data;
        }
    }
});