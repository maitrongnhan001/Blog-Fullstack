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
        user: false,
        userPermission: null,
    },
    getters: {
        getDeleteModalObj (state) {
            return state.deleteModalObj;
        },
        getUserPermission (state) {
            return state.userPermission;
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
        setUpdateUser (state, data) {
            state.user = data;
        },
        setUserPermission (state, data) {
            state.userPermission = data;
        }
    }
});