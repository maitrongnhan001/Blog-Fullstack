<template>
    <div>
        <div class="content">
			<div class="container">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management <Button @click="addModal=true " > <Icon type='md-add'/> Add a new role</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Role type</th>
								<th>Create at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
								<td>{{ role.id }}</td>
								<td class="_table_name">{{ role.roleName }}</td>
								<td>{{ role.created_at }}</td>
								<td>
									<Button type="info" size='small' @click='showEditModal(role, i)'>Edit</Button>
									<Button type="error" size='small' @click='showDeletingModal(role, i)' :loading='role.isDeleting' >Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<Modal
					v-model='addModal'
					title='Add role'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Role name: 
					<Input v-model="data.roleName" placeholder="Enter role name..." />

					<div slot='footer'>
						<Button type='default' @click="addModal=false" >Close</Button>
						<Button type='primary' @click='addRole' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Adding...' : 'Add role' }}</Button>
					</div>

				</Modal>

				<Modal
					v-model='editModal'
					title='Edit role'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Role name: 
					<Input v-model="editData.roleName" placeholder="Enter role name..." />

					<div slot='footer'>
						<Button type='default' @click="editModal=false" >Close</Button>
						<Button type='primary' @click='editRole' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Editing...' : 'Edit role' }}</Button>
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
				roleName: ''
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			isDeleting: false,
			roles: [],
			editData: {
				roleName: ''
			},
			index: -1,
			showDeleteModal: false,
			deleteItem: {},
			indexDelete: -1,
			websiteSettings: []
		}
	},
	methods: {
		async addRole () {
			if (this.data.roleName.trim() == '') return  this.error('Role name is required');
			const res = await this.callApi('POST', 'app/create_role', this.data);
			if (res.status === 201) {
				this.roles.unshift(res.data);
				this.success('Role has been add successfully!');
				this.addModal = false;
				this.data.roleName = '';
			} else {
				if (res.status === 422) {
					if (res.data.errors.roleName) {
						this.i(res.data.errors.roleName[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		async editRole () {
			if (this.editData.roleName.trim() == '') return  this.error('Role name is required');
			const res = await this.callApi('POST', 'app/edit_role', this.editData);
			if (res.status === 200) {
				this.roles[this.index].roleName = this.editData.roleName; 
				this.success('Role has been edited successfully!');
				this.editModal = false;
			} else {
				if (res.status === 422) {
					if (res.data.errors.roleName) {
						this.i(res.data.errors.roleName[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		showEditModal (role, index) {
			let obj = {
				id: role.id,
				roleName: role.roleName
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
			const deleteModalObj = {
				title: 'tag',
				showDeleteModal: true,
				deleteUrl: 'app/delete_tag',
				data: tag,
				isDeleted: false
			}

	 		this.$store.commit('setDeletingModalObj', deleteModalObj);

			// this.deleteItem = tag;
			this.indexDelete = index;
			// this.showDeleteModal = true;
		}
	},
	async created() {
		const res = await this.callApi('GET', 'app/get_roles');
		if (res.status === 200) {
			this.roles = res.data;
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
				this.tags.splice(this.indexDelete, 1);
			}
		}
	}
}
</script>