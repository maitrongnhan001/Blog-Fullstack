<template>
  <div>
    <Modal v-model="getDeleteModalObj.showDeleteModal" width="360">
      <p slot="header" style="color: #f60; text-align: center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align: center">
        <p>Are you sure you want to delete {{getDeleteModalObj.title}}?</p>
      </div>
      <div slot="footer">
        <Button
          type="default"
          size="large"
          @click="closeModal()"
          >Close</Button
        >
        <Button
          type="error"
          size="large"
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteTag()"
          >Delete</Button
        >
      </div>
    </Modal>
  </div>
</template>
<script>
import {mapGetters} from 'vuex';
export default {
    data () {
        return {
            isDeleting: false
        }
    },
    methods: {
        async deleteTag () {
          this.isDeleting = true;
          const res = await this.callApi('POST', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data);
          if (res.status === 200) {
              this.success('Tag has been deleted successfully!');
                    this.$store.commit('setDeletedModal', true);
          } else {
            this.swr();
                    this.$store.commit('setDeletedModal', false);
          } 
          this.isDeleting = false;
        },
        closeModal () {
          const deleteModalObj = {
            title: '',
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            isDeleted: false
          }

          this.$store.commit('setDeletingModalObj', deleteModalObj);
        }
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    }
};
</script>
