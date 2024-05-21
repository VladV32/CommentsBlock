<template>
  <div class="comment">
    <div class="comment-header">
      <img :src="comment.user?.avatar ?? defaultAvatar" alt="avatar" class="avatar">
      <div class="comment-info">
        <div>
          <strong>{{ comment.user?.name }}</strong>
        </div>
        <div>
          <span>{{ formatDate(comment.created_at) }}</span>
        </div>
        <div class="comment-actions">
          <i class="fas fa-thumbs-up"></i>
          <i class="fas fa-thumbs-down"></i>
          <i class="fas fa-reply"></i>
          <i class="fas fa-share"></i>
        </div>
      </div>
    </div>
    <p class="comment-text">{{ comment.text }}</p>
    <div v-if="comment.replies" class="replies">
      <Comment v-for="reply in comment.replies" :key="reply.id" :comment="reply"/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Comment',
  props: {
    comment: Object
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    }
  },
  data() {
    return {
      defaultAvatar: '/default-avatar.svg'
    };
  }
}
</script>

<style scoped>
.comment {
  margin: 15px 0;
}

.comment-header {
  display: flex;
  align-items: center;
  padding: 10px;
  background-color: lightgray;
  border-radius: 3px;
}

.comment-info {
  display: flex;
  flex-direction: row;
  gap: 10px;
}

.comment-info strong {
  font-size: 16px;
}

.comment-info span {
  font-size: 14px;
  color: #999;
}

.comment-actions {
  display: flex;
  gap: 10px;
  margin-top: 5px;
  color: lightskyblue;
}

.comment-actions svg {
  font-size: 18px;
  cursor: pointer;
}

.comment-actions svg:hover {
  color: #999;
}

.comment-text {
  font-size: 14px;
  color: #333;
  margin-top: 10px;
}

.replies {
  margin-left: 65px;
  margin-top: 15px;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 15px;
  background-color: white;
}
</style>
