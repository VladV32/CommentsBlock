<template>
  <div class="modal show" tabindex="-1" style="display: block;" role="dialog" v-if="isVisible">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add a comment</h5>
          <button type="button" class="close" aria-label="Close" @click="closePopup">
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
            <div class="form-group mb-3">
              <label for="avatar" class="form-label">Avatar:</label>
              <input type="file" class="form-control d-none" id="avatar" @change="handleFileChange($event, 'avatar')">
              <label class="btn btn-primary" for="avatar">{{ avatarLabel }}</label>
            </div>
            <div class="form-group mb-3">
              <label for="attach" class="form-label">Attach File:</label>
              <input type="file" class="form-control d-none" id="attach" @change="handleFileChange($event, 'attach')">
              <label class="btn btn-primary" for="attach">{{ attachLabel }}</label>
            </div>
            <div class="form-group">
              <label for="text">Comment:</label>
              <textarea class="form-control" v-model="form.text" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

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
        attach: null,
        text: ''
      },
      avatarLabel: 'Upload Avatar',
      attachLabel: 'Upload File'
    };
  },
  methods: {
    handleFileChange(event, type) {
      const file = event.target.files[0];
      const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
      const validFileType = (type === 'avatar' && validImageTypes.includes(file.type)) ||
          (type === 'attach' && (validImageTypes.includes(file.type) || (file.type === 'text/plain' && file.size <= 100 * 1024)));
      if (!validFileType) {
        alert(`Invalid ${type} file type. Only JPG, PNG, GIF for avatar and JPG, PNG, GIF, TXT under 100KB for attach are allowed.`);
        this.form[type] = null;
        if (type === 'avatar') {
          this.avatarLabel = 'Upload Avatar';
        } else if (type === 'attach') {
          this.attachLabel = 'Upload File';
        }
      } else {
        this.form[type] = file;
        if (type === 'avatar') {
          this.avatarLabel = file.name;
        } else if (type === 'attach') {
          this.attachLabel = file.name;
        }
      }
    },
    closePopup() {
      this.$emit('close');
    },
    async submitComment() {
      try {
        const formData = new FormData();
        Object.keys(this.form).forEach(key => {
          if (this.form[key]) formData.append(key, this.form[key]);
        });

        const response = await axios.post('/api/comments', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
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
