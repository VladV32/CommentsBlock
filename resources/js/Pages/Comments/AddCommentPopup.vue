<template>
  <div class="modal show" tabindex="-1" style="display: block;" role="dialog" v-if="isVisible">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add a comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closePopup">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form @submit.prevent="submitComment">
          <div class="modal-body">
            <div class="form-group">
              <label for="user_name">Name:</label>
              <input type="text" class="form-control" v-model="form.user_name" required>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" v-model="form.email" required>
            </div>
            <div class="form-group">
              <label for="home_page">Homepage:</label>
              <input type="url" class="form-control" v-model="form.home_page">
            </div>
            <!--        <div class="form-group">-->
            <!--          <label for="avatar">Avatar:</label>-->
            <!--          <input type="file" class="form-control-file" v-model="form.avatar" readonly>-->
            <!--        </div>-->
            <!--        <div class="g-recaptcha" :data-sitekey="recaptchaSiteKey"></div>-->
            <div class="form-group">
              <label for="text">Comment:</label>
              <textarea class="form-control" v-model="form.text" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: Boolean,
  },
  data() {
    return {
      form: {
        user_name: '',
        email: '',
        home_page: '',
        avatar: null,
        parent_id: null,
        //recaptchaSiteKey: comment.env.RECAPTCHA_SITE_KEY,
        text: ''
      }
    };
  },
  methods: {
    closePopup() {
      this.$emit('close');
    },
    async submitComment() {
      try {
        const response = await axios.post('/api/comments', this.form);
        this.$emit('comment-added', response.data);
        this.closePopup();
      } catch (error) {
        console.error('Error submitting comment:', error);
      }
    }
  }
};
</script>

<style scoped>

.form-group {
  margin-bottom: 15px;
}

.btn-primary {
  background-color: #007bff;
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.modal-header {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  padding: var(--bs-modal-header-padding);
  border-bottom: none;
  border-top-left-radius: var(--bs-modal-inner-border-radius);
  border-top-right-radius: var(--bs-modal-inner-border-radius);
}

.modal-header .close {
  padding: 1rem 1rem;
  margin: -1rem -1rem -1rem auto;
}

button.close {
  padding: 0;
  background-color: transparent;
  border: 0;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.close {
  float: right;
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1;
  color: #000;
  text-shadow: 0 1px 0 #fff;
  opacity: .5;
}
</style>