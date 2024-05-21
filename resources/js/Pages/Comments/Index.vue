<template>
  <div class="comments-container">
    <h1>Comments</h1>
    <button @click="showPopup = true" class="btn btn-outline-primary">Add a comment</button>
    <AddCommentPopup :isVisible="showPopup" @close="showPopup = false"
                     @comment-added="fetchComments(data.meta.current_page)"/>

    <Comment v-for="comment in data.comments" :key="comment.id" :comment="comment"/>
    <div v-if="data.comments && data.meta.total > data.meta.per_page">
      <Bootstrap5Pagination
          :data="data"
          @pagination-change-page="fetchComments"
      />
    </div>
  </div>
</template>

<script>
import AddCommentPopup from './AddCommentPopup.vue';
import Comment from './Comment.vue';
import {Bootstrap5Pagination} from 'laravel-vue-pagination';

export default {
  components: {
    AddCommentPopup,
    Comment,
    Bootstrap5Pagination
  },
  props: {
    initialComments: Object
  },
  data() {
    return {
      data : {
        comments: this.initialComments.data,
        meta: this.initialComments.meta,
        links: this.initialComments.links,
      },
      showPopup: false
    };
  },
  methods: {
    async fetchComments(page = 1) {
      try {
        const response = await axios.get('/api/comments', {
          params: {
            page: page
          }
        });
        this.$emit('update:comments', this.data = await response.data);
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    }
  }
}
</script>

<style scoped>
body {
  font-family: Arial, sans-serif;
}

.comments-container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 24px;
  margin-bottom: 20px;
}
</style>
