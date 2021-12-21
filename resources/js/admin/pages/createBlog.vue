<template>
    <div>
        <div class="content">
			<div class="container">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management <Button @click="addModal=true " v-if='isWritePermitted'> <Icon type='md-add'/> Add a new role</Button> </p>
                    <div class='_overflow _table_div blog_editor'>
                        <editor
                        ref='editor'
                        autofocus
                        holder-id='codex-editor'
                        save-button-id='save-button'
                        :init-data='initData'
                        @save='onSave'
                        />
                    </div>
                    <div class='_input_field'>
                        <Input type='text' placeholder='title'/>
                    </div>
                    <br>
                    <div class='_input_field'>
                        <Button @click='save' type='primary'>Save</Button>
                    </div>
				</div>
			</div>
		</div>
    </div>
</template>
<script>
export default {
	data() {
		return {
            config: {
                image: {
                    // Like in https://github.com/editor-js/image#config-params
                    field: 'image',
                    types: 'image/*',
                },
            },
            initData: '<h1>this is some data</h1>',
			data: {

            }
		}
	},
	methods: {
		async add () {
		},
        onSave (respone) {
            
        },
        async save () {
            const res = await this.$ref.editor.save();
        }
	},
	
}
</script>