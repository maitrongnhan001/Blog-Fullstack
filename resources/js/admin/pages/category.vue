<template>
    <div>
        <div class="content">
			<div class="container">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button @click="addModal=true " > <Icon type='md-add'/> Add category</Button> </p>

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
							<tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
								<td>{{ tag.id }}</td>
								<td class="_table_name">{{ tag.tagName }}</td>
								<td>{{ tag.created_at }}</td>
								<td>
									<Button type="info" size='small' @click='showEditModal(tag, i)'>Edit</Button>
									<Button type="error" size='small' @click='showDeletingModal(tag, i)' :loading='tag.isDeleting' >Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<Modal
					v-model='addModal'
					title='Add category'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Category name: 
					<Input v-model="data.tagName" placeholder="Enter category name..." />
                    <br/>
                    <br/>
                    <Upload
                        type="drag"
                        action="app/upload"
                        :headers="{'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest'}"
						:on-success='handleSuccess'
						:on-error='handleError'
						:format="['jpg', 'jpeg', 'png']"
						:max-size='2048'
						:on-format-error='handleFormatError'
						:on-exceeded-size='handleMaxSize'
                        >
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

					<div v-if='data.iconImage'>
						<img :src='`/upload/${data.iconImage}`' />
					</div>

					<div slot='footer'>
						<Button type='default' @click="addModal=false" >Close</Button>
						<Button type='primary' @click='addTag' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Adding...' : 'Add tag' }}</Button>
					</div>

				</Modal>

				<Modal
					v-model='editModal'
					title='Edit tag'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Tag name: 
					<Input v-model="editData.tagName" placeholder="Enter tag name..." />

					<div slot='footer'>
						<Button type='default' @click="editModal=false" >Close</Button>
						<Button type='primary' @click='editTag' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Editing...' : 'Edit tag' }}</Button>
					</div>

				</Modal>

				<Modal v-model="showDeleteModal" width="360">
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
				</Modal>

			</div>
		</div>
    </div>
</template>
<script>
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
			tags: [],
			editData: {
				tagName: ''
			},
			index: -1,
			showDeleteModal: false,
			deleteItem: {},
			indexDelete: -1,
            token: ''
		}
	},
	methods: {
		async addTag () {
			if (this.data.tagName.trim() == '') return  this.error('Tag name is required');
			const res = await this.callApi('POST', 'app/create_tag', this.data);
			if (res.status === 201) {
				this.tags.unshift(res.data);
				this.success('Tag has been add successfully!');
				this.addModal = false;
				this.data.tagName = '';
			} else {
				if (res.status === 422) {
					if (res.data.errors.tagName) {
						this.i(res.data.errors.tagName[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		async editTag () {
			if (this.editData.tagName.trim() == '') return  this.error('Tag name is required');
			const res = await this.callApi('POST', 'app/edit_tag', this.editData);
			if (res.status === 200) {
				this.tags[this.index].tagName = this.editData.tagName; 
				this.success('Tag has been edited successfully!');
				this.editModal = false;
			} else {
				if (res.status === 422) {
					if (res.data.errors.tagName) {
						this.i(res.data.errors.tagName[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		showEditModal (tag, index) {
			let obj = {
				id: tag.id,
				tagName: tag.tagName
			}

			this.index = index;
			this.editData = obj;
			this.editModal = true;
		},
		async deleteTag () {
			this.isDeleting = true;
			const res = await this.callApi('POST', 'app/delete_tag', this.deleteItem);
			if (res.status === 200) {
				this.tags.splice(this.indexDelete, 1);
				this.success('Tag has been deleted successfully!');
			} else {
				this.swr();
			}
			this.showDeletingModal = false;
			this.isDeleting = false;
		},
		showDeletingModal (tag, index) {
			this.deleteItem = tag;
			this.indexDelete = index;
			this.showDeleteModal = true;
		},
		handleSuccess (res, file) {
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
        }
	},
	async created() {
        this.token = window.Laravel.csrfToken;
		const res = await this.callApi('GET', 'app/get_tags');
		if (res.status === 200) {
			this.tags = res.data;
		} else {
			this.swr(); 
		}
	},
}
</script>
<style lang="">
    
</style>