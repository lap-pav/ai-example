<template>
  <div class="login-container">
    <div class="login-form">
      <div class="login-header">
        <h1>Welcome Back</h1>
        <p>Please sign in to your account</p>
      </div>

      <a-form
        :model="formData"
        :rules="rules"
        layout="vertical"
        @finish="handleLogin"
        @finishFailed="handleLoginFailed"
      >
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
            placeholder="Enter your password"
            size="large"
            :prefix="h(LockOutlined)"
          />
        </a-form-item>

        <a-form-item>
          <a-checkbox v-model:checked="rememberMe">
            Remember me
          </a-checkbox>
        </a-form-item>

        <a-form-item>
          <a-button
            type="primary"
            html-type="submit"
            size="large"
            block
            :loading="authStore.loading"
          >
            Sign In
          </a-button>
        </a-form-item>

        <div class="login-footer">
          <a-divider>
            <span class="divider-text">Don't have an account?</span>
          </a-divider>
          <a-button
            type="link"
            size="large"
            block
            @click="$router.push('/register')"
          >
            Create Account
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
import { MailOutlined, LockOutlined } from '@ant-design/icons-vue'
import { useAuthStore } from '@/stores/auth'
import type { LoginCredentials } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

// Form data
const formData = reactive<LoginCredentials>({
  email: '',
  password: ''
})

const rememberMe = ref(false)

// Validation rules
const rules = {
  email: [
    { required: true, message: 'Please enter your email' },
    { type: 'email', message: 'Please enter a valid email address' }
  ],
  password: [
    { required: true, message: 'Please enter your password' },
    { min: 8, message: 'Password must be at least 8 characters long' }
  ]
}

// Handle successful login
const handleLogin = async (values: LoginCredentials) => {
  try {
    await authStore.login(values)
    message.success('Login successful!')
    router.push('/')
  } catch (error: any) {
    console.error('Login error:', error)
    
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
      message.error('Login failed. Please try again.')
    }
  }
}

// Handle failed validation
const handleLoginFailed = (errorInfo: any) => {
  console.log('Login validation failed:', errorInfo)
  message.warning('Please check your input and try again.')
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
}

.login-form {
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.login-header {
  text-align: center;
  margin-bottom: 32px;
}

.login-header h1 {
  margin: 0 0 8px 0;
  color: #1f2937;
  font-size: 28px;
  font-weight: 700;
}

.login-header p {
  margin: 0;
  color: #6b7280;
  font-size: 16px;
}

.login-footer {
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
</style>
