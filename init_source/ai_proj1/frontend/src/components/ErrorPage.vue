<template>
  <div class="error-container">
    <div class="error-content">
      <div class="error-icon">
        <ExclamationCircleOutlined />
      </div>
      <h1 class="error-title">{{ title }}</h1>
      <p class="error-message">{{ message }}</p>
      <div class="error-actions">
        <a-button type="primary" size="large" @click="$router.push('/')">
          Go Home
        </a-button>
        <a-button size="large" @click="$router.go(-1)">
          Go Back
        </a-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ExclamationCircleOutlined } from '@ant-design/icons-vue'

interface Props {
  title?: string
  message?: string
}

withDefaults(defineProps<Props>(), {
  title: 'Something went wrong',
  message: 'An unexpected error occurred. Please try again later.'
})
</script>

<style scoped>
.error-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 20px;
}

.error-content {
  text-align: center;
  background: white;
  padding: 60px 40px;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 100%;
}

.error-icon {
  font-size: 72px;
  color: #ff7875;
  margin-bottom: 24px;
}

.error-title {
  font-size: 32px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 16px;
}

.error-message {
  font-size: 16px;
  color: #6b7280;
  margin-bottom: 32px;
  line-height: 1.6;
}

.error-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}

:deep(.ant-btn) {
  border-radius: 8px;
  font-weight: 600;
  height: 40px;
  padding: 0 24px;
}
</style>
