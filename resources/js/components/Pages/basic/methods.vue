<template>
    <div>
        <h1>Method</h1>
        <h2>Count: {{count}}</h2>
        <button @click="updateCounter(1)"> +1 </button>
        <button @click="updateCounter(-1)"> -1 </button>

        <div v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
            <h1>{{blog.title}}</h1>
            <p>{{blog.post}}</p>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            count: 0,
            blogs: []
        }
    },
    methods: {
        updateCounter (number) {
            this.count += number;
        }
    },
    created() {
        let posts = [
            {
                title: "blog title 1", post: 'blog content 1', id: 1
            },
            {
                title: "blog title 2", post: 'blog content 2', id: 2
            },
            {
                title: "blog title 3", post: 'blog content 3', id: 3
            },
            {
                title: "blog title 4", post: 'blog content 4', id: 4
            }
        ];

        this.blogs = posts;
    }
}
</script>