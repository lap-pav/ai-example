<template>
  <div class="register-container">
    <div class="register-form">
      <div class="register-header">
        <h1>Create Account</h1>
        <p>Join us and get started today</p>
      </div>

      <a-form
        :model="formData"
        :rules="rules"
        layout="vertical"
        @finish="handleRegister"
        @finishFailed="handleRegisterFailed"
      >
        <a-form-item
          label="Full Name"
          name="name"
          has-feedback
        >
          <a-input
            v-model:value="formData.name"
            placeholder="Enter your full name"
            size="large"
            :prefix="h(UserOutlined)"
          />
        </a-form-item>

        <a-form-item
          label="Email"
          name="email"
          has-feedback
        >
          <a-input
            v-model:value="formData.email"
            type="email"
            placeholder="Enter your email"
            size="large"
            :prefix="h(MailOutlined)"
          />
        </a-form-item>

        <a-form-item
          label="Password"
          name="password"
          has-feedback
        >
          <a-input-password
            v-model:value="formData.password"
            placeholder="Create a password"
            size="large"
            :prefix="h(LockOutlined)"
          />
        </a-form-item>

        <a-form-item
          label="Confirm Password"
          name="password_confirmation"
          has-feedback
        >
          <a-input-password
            v-model:value="formData.password_confirmation"
            placeholder="Confirm your password"
            size="large"
            :prefix="h(LockOutlined)"
          />
        </a-form-item>

        <a-form-item>
          <a-checkbox v-model:checked="agreeToTerms">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
          </a-checkbox>
        </a-form-item>

        <a-form-item>
          <a-button
            type="primary"
            html-type="submit"
            size="large"
            block
            :loading="authStore.loading"
            :disabled="!agreeToTerms"
          >
            Create Account
          </a-button>
        </a-form-item>

        <div class="register-footer">
          <a-divider>
            <span class="divider-text">Already have an account?</span>
          </a-divider>
          <a-button
            type="link"
            size="large"
            block
            @click="$router.push('/login')"
          >
            Sign In
          </a-button>
        </div>
      </a-form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, h } from 'vue'
import { useRouter } from 'vue-router'
import { message } from 'ant-design-vue'
import { UserOutlined, MailOutlined, LockOutlined } from '@ant-design/icons-vue'
import { useAuthStore } from '@/stores/auth'
import type { RegisterData } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

// Form data
const formData = reactive<RegisterData>({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const agreeToTerms = ref(false)

// Validation rules
const rules = {
  name: [
    { required: true, message: 'Please enter your full name' },
    { min: 2, message: 'Name must be at least 2 characters long' }
  ],
  email: [
    { required: true, message: 'Please enter your email' },
    { type: 'email', message: 'Please enter a valid email address' }
  ],
  password: [
    { required: true, message: 'Please create a password' },
    { min: 8, message: 'Password must be at least 8 characters long' }
  ],
  password_confirmation: [
    { required: true, message: 'Please confirm your password' },
    {
      validator: (rule: any, value: string) => {
        if (value && value !== formData.password) {
          return Promise.reject('Passwords do not match')
        }
        return Promise.resolve()
      }
    }
  ]
}

// Handle successful registration
const handleRegister = async (values: RegisterData) => {
  try {
    await authStore.register(values)
    message.success('Account created successfully!')
    router.push('/')
  } catch (error: any) {
    console.error('Registration error:', error)
    
    if (error.response?.data?.errors) {
      // Handle validation errors
      const errors = error.response.data.errors
      Object.keys(errors).forEach(key => {
        errors[key].forEach((msg: string) => {
          message.error(msg)
        })
      })
    } else if (error.response?.data?.message) {
      message.error(error.response.data.message)
    } else {
      message.error('Registration failed. Please try again.')
    }
  }
}

// Handle failed validation
const handleRegisterFailed = (errorInfo: any) => {
  console.log('Registration validation failed:', errorInfo)
  message.warning('Please check your input and try again.')
}
</script>

<style scoped>
.register-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
}

.register-form {
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 450px;
}

.register-header {
  text-align: center;
  margin-bottom: 32px;
}

.register-header h1 {
  margin: 0 0 8px 0;
  color: #1f2937;
  font-size: 28px;
  font-weight: 700;
}

.register-header p {
  margin: 0;
  color: #6b7280;
  font-size: 16px;
}

.register-footer {
  margin-top: 24px;
}

.divider-text {
  color: #6b7280;
  font-size: 14px;
}

:deep(.ant-form-item-label > label) {
  font-weight: 600;
  color: #374151;
}

:deep(.ant-input-affix-wrapper),
:deep(.ant-input) {
  border-radius: 8px;
}

:deep(.ant-btn) {
  border-radius: 8px;
  font-weight: 600;
}

:deep(.ant-btn-primary) {
  background: #667eea;
  border-color: #667eea;
  height: 48px;
}

:deep(.ant-btn-primary:hover) {
  background: #5a67d8;
  border-color: #5a67d8;
}

:deep(.ant-checkbox-wrapper) {
  font-size: 14px;
}
</style>
