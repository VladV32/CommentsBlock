<template>
  <div class="comments-container">
    <h1>Comments</h1>
    <button @click="showPopup = true" class="btn btn-outline-primary">Add a comment</button>
    <AddCommentPopup :isVisible="showPopup" @close="showPopup = false"/>

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
      data: {
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
    },
    handleNewComment(comment) {
      const addReplyToParent = (comments, reply) => {
        for (let i = 0; i < comments.length; i++) {
          if (comments[i].id === reply.parent_id) {
            if (!comments[i].replies) {
              comments[i].replies = [];
            }
            comments[i].replies.unshift(reply);
            return true;
          }
          if (comments[i].replies && comments[i].replies.length) {
            if (addReplyToParent(comments[i].replies, reply)) {
              return true;
            }
          }
        }
        return false;
      };

      if (!addReplyToParent(this.data.comments, comment)) {
        this.data.comments.unshift(comment);
      }
    }
  },
  created() {
    window.Echo.channel('comments')
        .listen('CommentCreated', (e) => {
          this.handleNewComment(e.comment);
        });
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
