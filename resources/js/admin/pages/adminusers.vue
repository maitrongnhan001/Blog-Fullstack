<template>
    <div>
        <div class="content">
			<div class="container">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true " > <Icon type='md-add'/> Add admin</Button> </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
                                <th>User type</th>
                                <th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, i) in users" :key="i" v-if="users.length">
								<td>{{ user.id }}</td>
								<td class="_table_name">{{ user.fullName }}</td>
								<td>{{ user.email }}</td>
                                <td>{{ userType[user.role_id] }}</td>
                                <td>{{ user.created_at }}</td>
								<td>
									<Button type="info" size='small' @click='showEditModal(user, i)'>Edit</Button>
									<Button type="error" size='small' @click='showDeletingModal(user, i)' :loading='user.isDeleting' >Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<Modal
					v-model='addModal'
					title='Add admin'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Admin name: 
					<Input type='text' v-model="data.fullName" placeholder="Enter admin name..." />
                    <br/>
                    Email:
                    <Input type='email' v-model="data.email" placeholder="Enter email..." />
                    <br/>
                    Password:
                    <Input type='password' v-model="data.password" placeholder="Enter password..." />
                    <br/>
                    User type:
                    <Select v-model="data.role_id" placeholder='Chooese user type'>
                        <Option :value='role.id' v-for='(role, i) in roles' :key='i' v-if='roles.length'>{{role.roleName}}</Option>
                    </Select>
					<div slot='footer'>
						<Button type='default' @click="addModal=false" >Close</Button>
						<Button type='primary' @click='addAdmin' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Adding...' : 'Add admin' }}</Button>
					</div>

				</Modal>

				<Modal
					v-model='editModal'
					title='Edit admin'
					:mask-closable = 'false'
					:closable= 'false'
				>
					Tag name: 
					Admin name: 
					<Input type='text' v-model="editData.fullName" placeholder="Enter admin name..." />
                    <br/>
                    Email:
                    <Input type='email' v-model="editData.email" placeholder="Enter email..." />
                    <br/>
                    Password:
                    <Input type='password' v-model="editData.password" placeholder="Enter password..." />
                    <br/>
                    User type:
                    <Select v-model="editData.role_id" placeholder='Chooese user type'>
                        <Option :value='role.id' v-for='(role, i) in roles' :key='i' v-if='roles.length'>{{role.roleName}}</Option>
                    </Select>

					<div slot='footer'>
						<Button type='default' @click="editModal=false" >Close</Button>
						<Button type='primary' @click='editAdmin' :disabled='isAdding' :loading='isAdding'>{{ isAdding ? 'Editing...' : 'Edit Admin' }}</Button>
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
				fullName: '',
                email: '',
                password: '',
                role_id: null
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			isDeleting: false,
			users: [],
			roles: [],
			userType: [],
			editData: {
				tagName: ''
			},
			index: -1,
			showDeleteModal: false,
			deleteItem: {},
			indexDelete: -1,
			websiteSettings: []
		}
	},
	methods: {
		async addAdmin () {
			if (this.data.fullName.trim() == '')  this.error('Admin name is required');
            if (this.data.email.trim() == '')  this.error('Email is required');
            if (this.data.password.trim() == '') return this.error('Password is required');
            if (!this.data.role_id) return  this.error('Type admin is required');
			const res = await this.callApi('POST', 'app/create_user', this.data);
			if (res.status === 201) {
				this.users.unshift(res.data);
				this.success('Admin has been added successfully!');
				this.addModal = false;
				this.data.fullName = '';
                this.data.email = '';
                this.data.password = '';
                this.data.userType = 'admin';
			} else {
				if (res.status === 422) {
                    for (let index in res.data.errors) {
                        this.error(res.data.errors[index][0]);
                    };
				} else {
					this.swr();
				}
			}
		},
		async editAdmin () {
			if (this.editData.fullName.trim() == '')  this.error('Admin name is required');
            if (this.editData.email.trim() == '') return this.error('Email is required');
            if (!this.editData.role_id) return  this.error('Type admin is required');
			const res = await this.callApi('POST', 'app/edit_user', this.editData);
			if (res.status === 200) {
				this.users[this.index] = this.editData; 
				this.success('Admin has been edited successfully!');
				this.editModal = false;
			} else {
				if (res.status === 422) {
                    for (let index in res.data.errors) {
                        this.error(res.data.errors[index][0]);
                    };
				} else {
					this.swr();
				}
			}
		},
		showEditModal (user, index) {
			let obj = {
				id: user.id,
				fullName: user.fullName,
				email: user.email,
				role_id: user.role_id
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
		showDeletingModal (admin, index) {
			const deleteModalObj = {
				title: 'admin',
				showDeleteModal: true,
				deleteUrl: 'app/delete_user',
				data: admin,
				isDeleted: false
			}

	 		this.$store.commit('setDeletingModalObj', deleteModalObj);

			// this.deleteItem = tag;
			this.indexDelete = index;
			// this.showDeleteModal = true;
		}
	},
	async created() {
		const [res, resRole] = await Promise.all([
			this.callApi('GET', 'app/get_users'),
			this.callApi('GET', 'app/get_roles')
		]);
		if (res.status === 200) {
			this.users = res.data;
		} else {
			this.swr(); 
		}

		if (resRole.status === 200) {
			this.roles = resRole.data;
		} else {
			this.swr(); 
		}

		let userType = [];
		for (let index in resRole.data) {
			userType[resRole.data[index].id] = resRole.data[index].roleName;
		}

		this.userType = userType;
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
				this.users.splice(this.indexDelete, 1);
			}
		}
	}
}
</script>