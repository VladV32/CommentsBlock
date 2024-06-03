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
              <label for="user_name" class="required">Name:</label>
              <input type="text" class="form-control" :class="{'is-invalid': errors.user_name}" v-model="form.user_name"
                     @input="saveFormData" required>
              <div v-if="errors.user_name" class="invalid-feedback">{{ errors.user_name[0] }}</div>
            </div>
            <div class="form-group">
              <label for="email" class="required">Email:</label>
              <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="form.email"
                     @input="saveFormData" required>
              <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
            </div>
            <div class="form-group">
              <label for="home_page">Homepage:</label>
              <input type="url" class="form-control" :class="{'is-invalid': errors.home_page}" v-model="form.home_page"
                     @input="saveFormData">
              <div v-if="errors.home_page" class="invalid-feedback">{{ errors.home_page[0] }}</div>
            </div>
            <div class="form-group mb-3">
              <label for="avatar" class="form-label">Avatar:</label>
              <input type="file" class="form-control d-none" id="avatar" @change="handleFileChange($event, 'avatar')">
              <label class="btn btn-primary" for="avatar">{{ avatarLabel }}</label>
              <div v-if="errors.avatar" class="invalid-feedback">{{ errors.avatar[0] }}</div>
            </div>
            <div class="form-group mb-3">
              <label for="attach" class="form-label">Attach File:</label>
              <input type="file" class="form-control d-none" id="attach" @change="handleFileChange($event, 'attach')">
              <label class="btn btn-primary" for="attach">{{ attachLabel }}</label>
              <div v-if="errors.attach" class="invalid-feedback">{{ errors.attach[0] }}</div>
            </div>
            <div class="form-group">
              <label for="text">Comment:</label>
              <textarea class="form-control" :class="{'is-invalid': errors.text}" v-model="form.text"
                        @input="saveFormData" required></textarea>
              <div v-if="errors.text" class="invalid-feedback">{{ errors.text[0] }}</div>
            </div>
            <div class="form-group">
              <label for="captcha" class="required">Captcha:</label>
              <CaptchaImage ref="captchaRef"/>
              <input type="text" class="form-control" :class="{'is-invalid': errors.captcha}" v-model="captcha"
                     required>
              <div v-if="errors.captcha" class="invalid-feedback">
                {{ errors.captcha }}
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Send</button>
            <div v-if="errors.oops" class="invalid-feedback">{{ errors.oops }}</div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Cookies from 'js-cookie';
import { CaptchaImage } from "vue3-captcha-canvas";

export default {
  props: {
    isVisible: Boolean,
    parentId: {
      type: Number,
      default: null
    }
  },
  components: {
    CaptchaImage
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
      captcha: '',
      oops: null,
      avatarLabel: 'Upload Avatar',
      attachLabel: 'Upload File',
      errors: {}
    };
  },
  methods: {
    async recaptcha() {
      await this.$recaptchaLoaded()
      return await this.$recaptcha('submit_comment')
    },
    handleFileChange(event, type) {
      const file = event.target.files[0];
      const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
      const validFileType = (type === 'avatar' && validImageTypes.includes(file.type)) ||
          (type === 'attach' && (validImageTypes.includes(file.type) || (file.type === 'text/plain' && file.size <= 100 * 1024)));
      if (!validFileType) {
        let $message = `Invalid ${type} file type. Only JPG, PNG, GIF for avatar and JPG, PNG, GIF, TXT under 100KB for attach are allowed.`
        this.errors.attach = [$message];
        this.form[type] = null;
        if (type === 'avatar') {
          this.avatarLabel = 'Upload Avatar';
        } else if (type === 'attach') {
          this.attachLabel = 'Upload File';
        }
      } else {
        this.errors.attach = null;
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
    saveFormData() {
      Cookies.set('commentForm', JSON.stringify(this.form), { expires: 7 });
    },
    loadFormData() {
      const savedForm = Cookies.get('commentForm');
      if (savedForm) {
        const form = JSON.parse(savedForm);
        this.form.user_name = form.user_name ?? '';
        this.form.email = form.email ?? '';
        this.form.home_page = form.home_page ?? '';
        this.form.text = '';
      }
    },
    async submitComment() {
      const recaptchaToken = await this.recaptcha();
      if (!recaptchaToken) {
        this.errors.oops = 'No complete CAPTCHA.';
        return;
      }

      if (!this.$refs.captchaRef.verify(this.captcha)) {
        this.errors.captcha = 'Captcha verification failed';
        return;
      }

      try {
        const formData = new FormData();
        Object.keys(this.form).forEach(key => {
          if (this.form[key]) formData.append(key, this.form[key]);
        });
        if (this.parentId) {
          formData.append('parent_id', this.parentId);
        }
        formData.append('g-recaptcha-response', recaptchaToken);

        const response = await axios.post('/api/comments', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        this.$emit('comment-added', response.data);
        this.closePopup();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          this.errors.oops = 'oops, there was an error';
          console.error('Error submitting comment:', error.response.data.errors);
        }
      }
    }
  },
  created() {
    this.loadFormData();
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

.invalid-feedback {
  display: block;
  color: #dc3545;
}

.is-invalid {
  border-color: #dc3545;
}

.required::after {
  content: " *";
  color: #dc3545;
}
</style>
