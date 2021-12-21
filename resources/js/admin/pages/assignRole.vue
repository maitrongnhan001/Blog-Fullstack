<template>
    <div>
        <div class="content">
			<div class="container">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management 
                        <Select v-model="data.role_id" placeholder='Chooese user type' style='width:300px' @on-change='HandleClickRoleSelect(data.role_id)'>
                            <Option :value='role.id' v-for='(role, i) in roles' :key='i' v-if='roles.length'>{{role.roleName}}</Option>
                        </Select>
                    </p>

					<div class="_overflow _table_div">
						<table class="_table" v-if='isUpdatePermitted' >
								<!-- TABLE TITLE -->
							<tr>
                                <th>Resource name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(resource, i) in resources" :key="i" v-if="resources.length">
								<td>{{resource.resourceName}}</td>
                                <td>
                                    <Checkbox v-model='resource.read'>Read</Checkbox>
                                </td>
                                <td>
                                    <Checkbox v-model='resource.write'>Write</Checkbox>
                                </td>
                                <td>
                                    <Checkbox v-model='resource.update'>Update</Checkbox>
                                </td>
                                <td>
                                    <Checkbox v-model='resource.delete'>Delete</Checkbox>
                                </td>
							</tr>
								<!-- ITEMS -->
						</table>
                        <br>
                        <div class='center'>
                            <Button type='primary' :loading='isSending' :disabled='isSending' @click='assignRole'>{{ isSending ? 'Sending' : 'Assign' }}</Button>
                        </div>
					</div>
				</div>
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
				roleName: '',
                role_id: null
			},
			roles: [],
            isSending: false,
            resources: [
                {resourceName: 'Home', read: false, write: false, update: false, delete: false, name: '/'},
                {resourceName: 'Tags', read: false, write: false, update: false, delete: false, name: 'tags'},
                {resourceName: 'Category', read: false, write: false, update: false, delete: false, name: 'category'},
                {resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name: 'createBlog'},
                {resourceName: 'Adminuser', read: false, write: false, update: false, delete: false, name: 'adminuser'},
                {resourceName: 'Role', read: false, write: false, update: false, delete: false, name: 'role'},
                {resourceName: 'Assignrole', read: false, write: false, update: false, delete: false, name: 'assignrole'}
            ],
            defaultResources: [
                {resourceName: 'Home', read: false, write: false, update: false, delete: false, name: '/'},
                {resourceName: 'Tags', read: false, write: false, update: false, delete: false, name: 'tags'},
                {resourceName: 'Category', read: false, write: false, update: false, delete: false, name: 'category' },
                {resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name: 'createBlog'},
                {resourceName: 'Adminuser', read: false, write: false, update: false, delete: false, name: 'adminuser'},
                {resourceName: 'Role', read: false, write: false, update: false, delete: false, name: 'role'},
                {resourceName: 'Assignrole', read: false, write: false, update: false, delete: false, name: 'assignrole'}
            ]
        }
	},
    methods: {
        async assignRole () {
            let data = JSON.stringify(this.resources);
            const res = await this.callApi('POST', 'app/assign_roles', {'permission': data, 'id' : this.data.role_id});

            if (res.status === 200) {
                const roles = this.roles;
                this.success('Role has been assign successfully!');
                const index  = roles.findIndex(role => role.id == this.data.role_id);
                this.roles[index].permission = data;
            } else {
                this.swr();
            }
        },
        HandleClickRoleSelect (role_id) {
            const id = role_id;
            const roles = this.roles;
            const index = roles.findIndex(role => role.id == id);
            if (this.roles[index].permission) {
                this.resources = JSON.parse(this.roles[index].permission);
            } else {
                this.resources = this.defaultResources;
            }
        }
    },
	async created() {
		const res = await this.callApi('GET', 'app/get_roles');
		if (res.status === 200) {
			this.roles = res.data;
            if (res.data.length) {
                this.data.role_id = res.data[0].id;
                if (res.data[0].permission) {
                    this.resources = JSON.parse(res.data[0].permission);
                }
            }
		} else {
			this.swr(); 
		}
	},
}
</script>

<style scope>
select {
    height: 40px;
    font-weight: both;
}

</style>