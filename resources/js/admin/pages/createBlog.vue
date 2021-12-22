<template>
  <div>
    <div class="content">
      <div class="container">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
        >
          <p class="_title0">
            Role Management
            <Button @click="addModal = true" v-if="isWritePermitted">
              <Icon type="md-add" /> Add a new role</Button
            >
          </p>
          <div class="_overflow _table_div blog_editor">
            <editor
              ref="editor"
              autofocus
              holder-id="codex-editor"
              save-button-id="save-button"
              :init-data="initData"
              @save="onSave"
              :config="config"
            />
          </div>
          <div class="_input_field">
            <Input type="text" placeholder="title" />
          </div>
          <br />
          <div class="_input_field">
            <Button @click="save" type="primary">Save</Button>
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
        tools: {
          header: require("@editorjs/header"),
          embed: require("@editorjs/embed"),
          table: require("@editorjs/table"),
          image: require("@editorjs/image"),
          paragraph: require("@editorjs/paragraph"),
          raw: require("@editorjs/raw"),
        },
      },
      initData: null,
      data: {
        title: "",
        post: "",
        post_excerpt: "",
        metaDescription: "",
        category_id: [],
        tag_id: [],
        jsonData: null,
      },
      articleHTML: "",
      category: [],
      tag: [],
      isCreating: false,
    };
  },
  methods: {
    async add() {},
    onSave() {
        console.log('respone');
    },
    async save() {
      //const res = await this.$ref.editor.save();
      console.log('hello');
    },
  },
};
</script>

<style>
.blog_editor {
  width: 717px;
  margin-left: 160px;
  padding: 4px 7px;
  font-size: 14px;
  border: 1px solid #dcdee2;
  border-radius: 4px;
  color: #515a6e;
  background-color: #fff;
  background-image: none;
  z-index: -1;
}
.blog_editor:hover {
  border: 1px solid #57a3f3;
}

._input_field {
  margin: 20px 0 20px 160px;
  width: 717px;
}
</style>
