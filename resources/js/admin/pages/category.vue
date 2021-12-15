<template>
  <div>
    <div class="content">
      <div class="container">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
        >
          <p class="_title0">
            Category
            <Button @click="addModal = true"> <Icon type="md-add" /> Add category</Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Category name</th>
                <th>Create at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr
                v-for="(category, i) in categoryLists"
                :key="i"
                v-if="categoryLists.length"
              >
                <td>{{ category.id }}</td>
                <td class="table_image">
                  <img :src="category.iconImage" />
                </td>
                <td class="_table_name">{{ category.categoryName }}</td>
                <td>
                  <Button type="info" size="small" @click="showEditModal(category, i)"
                    >Edit</Button
                  >
                  <Button
                    type="error"
                    size="small"
                    @click="showDeletingModal(category, i)"
                    :loading="category.isDeleting"
                    >Delete</Button
                  >
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>

        <Modal
          v-model="addModal"
          title="Add category"
          :mask-closable="false"
          :closable="false"
        >
          Category name:
          <Input v-model="data.categoryName" placeholder="Enter category name..." />
          <br />
          <br />
          <Upload
            ref="uploads"
            type="drag"
            action="app/upload"
            :headers="{ 'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest' }"
            :on-success="handleSuccess"
            :on-error="handleError"
            :format="['jpg', 'jpeg', 'png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>

          <div class="demo-upload-list" v-if="data.iconImage">
            <img :src="`/upload/${data.iconImage}`" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage">Delete Image</Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="addModal = false">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding..." : "Add category" }}</Button
            >
          </div>
        </Modal>

        <Modal
          v-model="editModal"
          title="Edit category"
          :mask-closable="false"
          :closable="false"
        >
          <Input v-model="editData.categoryName" placeholder="Enter category name..." />
          <br />
          <br />
          <Upload
            v-if="isIconImageNew"
            ref="editDataUploads"
            type="drag"
            action="app/upload"
            :headers="{ 'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest' }"
            :on-success="handleSuccess"
            :on-error="handleError"
            :format="['jpg', 'jpeg', 'png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag files here to upload</p>
            </div>
          </Upload>

          <div class="demo-upload-list" v-if="editData.iconImage">
            <img :src="`${editData.iconImage}`" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage(false)"
                >Delete Image</Icon
              >
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="closeEditModal()">Close</Button>
            <Button
              type="primary"
              @click="editCategory"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Editing..." : "Edit category" }}</Button
            >
          </div>
        </Modal>

        <!-- <Modal v-model="showDeleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete tag?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="isDeleting" :disabled='isDeleting' @click="deleteTag()">Delete</Button>
					</div>
				</Modal> -->
        <deleteModal></deleteModal>
      </div>
    </div>
  </div>
</template>
<script>
import deleteModal from '../components/deleteModal.vue';
import {mapGetters} from 'vuex';
export default {
	data() {
		return {
			data : {
				iconImage: '',
				categoryName: ''
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			isDeleting: false,
			categoryLists: [],
			editData: {
				iconImage: '',
				categoryName: ''
			},
			index: -1,
			showDeleteModal: false,
			deleteItem: {},
			indexDelete: -1,
            token: '',
			isIconImageNew: false,
			isEditing: false,
			websiteSettings: []
		}
	},
	methods: {
		async addCategory () {
			if (this.data.categoryName.trim() == '') return  this.error('Category name is required');
			if (this.data.iconImage.trim() == '') return  this.error('Icon image name is required');
			this.data.iconImage = `/upload/${this.data.iconImage}`;
			const res = await this.callApi('POST', 'app/create_category', this.data);
			if (res.status === 201) {
				this.categoryLists.unshift(res.data);
				this.success('Category has been add successfully!');
				this.addModal = false;
				this.data.categoryName = '';
				this.data.iconImage = '';
			} else {
				if (res.status === 422) {
					if (res.data.errors.categoryName) {
						this.i(res.data.errors.categoryName[0]);
					}
					if (res.data.errors.iconImage) {
						this.i(res.data.errors.iconImage[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		async editCategory () {
			if (this.editData.categoryName.trim() == '') return  this.error('Category name is required');
			if (this.editData.iconImage.trim() == '') return  this.error('Icon image name is required');
			const res = await this.callApi('POST', 'app/edit_category', this.editData);
			if (res.status === 200) {
				this.categoryLists[this.index].categoryName = this.editData.categoryName;
				this.categoryLists[this.index].iconImage = this.editData.iconImage;
				this.success('Tag has been edited successfully!');
				this.editModal = false;
			} else {
				if (res.status === 422) {
					if (res.data.errors.categoryName) {
						this.i(res.data.errors.categoryName[0]);
					}
					if (res.data.errors.iconImage) {
						this.i(res.data.errors.iconImage[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		showEditModal (category, index) {

			this.index = index;
			this.editData = category;
			this.editModal = true;
			this.isEditing = true;
		},
		showDeletingModal (category, index) {
			const deleteModalObj = {
				title: 'category',
				showDeleteModal: true,
				deleteUrl: 'app/delete_category',
				data: category,
				isDeleted: false
			}

			this.$store.commit('setDeletingModalObj', deleteModalObj);

			// this.deleteItem = tag;
			this.indexDelete = index;
			// this.showDeleteModal = true;
			// this.isEditing = true;
		},
		handleSuccess (res, file) {
			if (this.isEditing) {
				return this.editData.iconImage = `/upload/${res}`;
				this.isIconImageNew = false;
			}

            this.data.iconImage = res;
        },
		handleError (res, file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: `${file.error.file.length ? file.error.file[0] : 'Something went wrong!'}`
            });
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
		async deleteImage (isAdd = true) {
			let Image;
			if (!isAdd) {
				this.isIconImageNew = true;

				Image = this.editData.iconImage;

				//this.$refs.editDataUploads.clearFiles();

				this.editData.iconImage = '';
			} else {
				Image = this.data.iconImage;

				this.$refs.uploads.clearFiles();

				this.data.iconImage = '';
			}

			const res = await this.callApi('POST', 'app/delete_image', {imageName: Image});

			if (res.status != 200) {
				this.data.iconImage = image;
				this.swr();
			}
		},
		closeEditModal () {
			this.editModal = false;
			this.isEditing = false;
		}
	},
	async created () {
        this.token = window.Laravel.csrfToken;
		const res = await this.callApi('GET', 'app/get_categories');
		if (res.status === 200) {
			this.categoryLists = res.data;
		} else {
			this.swr();
		}
	},
	components: {
		deleteModal
	},
	computed: {
		...mapGetters(['getDeleteModalObj'])
	},
	watch: {
		getDeleteModalObj(obj) {
			if (obj.isDeleted) {
				this.categoryLists.splice(this.indexDelete, 1);
			}
		}
	}
}
</script>
<style lang=""></style>
