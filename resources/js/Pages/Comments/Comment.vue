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
          <font-awesome-icon icon="thumbs-up" />
          <font-awesome-icon icon="thumbs-down" />
          <font-awesome-icon icon="reply" @click="showReplyForm(comment.id)" />
          <font-awesome-icon icon="share" />
        </div>
      </div>
    </div>
    <p class="comment-text" :class="{ 'text-truncate': isTruncated }" @click="toggleTruncate" v-html="comment.text"></p>
    <div v-if="comment.replies" class="replies">
      <Comment v-for="reply in comment.replies" :key="reply.id" :comment="reply" @comment-added="fetchComments"/>
    </div>
    <AddCommentPopup
        v-if="isReplying && replyParentId === comment.id"
        :isVisible="isReplying"
        @close="closeReplyForm"
        @comment-added="handleCommentAdded"
        :parentId="replyParentId"
    />
  </div>
</template>

<script>
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {faReply, faThumbsUp, faThumbsDown, faShare} from '@fortawesome/free-solid-svg-icons';
import {library} from '@fortawesome/fontawesome-svg-core';
import AddCommentPopup from './AddCommentPopup.vue';

library.add(faReply, faThumbsUp, faThumbsDown, faShare);

export default {
  name: 'Comment',
  props: {
    comment: Object
  },
  components: {
    FontAwesomeIcon,
    AddCommentPopup
  },
  data() {
    return {
      defaultAvatar: '/default-avatar.svg',
      isReplying: false,
      isTruncated: true,
      replyParentId: null
    };
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    showReplyForm(parentId) {
      this.isReplying = true;
      this.replyParentId = parentId;
    },
    closeReplyForm() {
      this.isReplying = false;
      this.replyParentId = null;
    },
    handleCommentAdded() {
      this.$emit('comment-added');
      this.closeReplyForm();
    },
    fetchComments() {
      // This method should be implemented in the parent component to fetch and update the comments list
      this.$emit('comment-added');
    },
    toggleTruncate() {
      this.isTruncated = !this.isTruncated;
    }
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
  background-color: whitesmoke;
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
  color: lightsteelblue;
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
  width: 57px;
  height: 57px;
  border-radius: 50%;
  margin-right: 15px;
  background-color: white;
  border: solid white;
  padding: 2px;
}
</style>
