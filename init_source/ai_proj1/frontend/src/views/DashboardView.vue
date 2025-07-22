<template>
  <div class="dashboard">
    <a-layout>
      <a-layout-header class="header">
        <div class="header-content">
          <div class="logo">
            <h2>Dashboard</h2>
          </div>
          <div class="user-menu">
            <a-dropdown>
              <a-button type="text" class="user-button">
                <UserOutlined />
                {{ authStore.user?.name }}
                <DownOutlined />
              </a-button>
              <template #overlay>
                <a-menu>
                  <a-menu-item key="profile">
                    <UserOutlined />
                    Profile
                  </a-menu-item>
                  <a-menu-item key="settings">
                    <SettingOutlined />
                    Settings
                  </a-menu-item>
                  <a-menu-divider />
                  <a-menu-item key="logout" @click="handleLogout">
                    <LogoutOutlined />
                    Logout
                  </a-menu-item>
                </a-menu>
              </template>
            </a-dropdown>
          </div>
        </div>
      </a-layout-header>

      <a-layout-content class="content">
        <div class="welcome-section">
          <a-card class="welcome-card">
            <template #title>
              <div class="card-title">
                <UserOutlined style="margin-right: 8px;" />
                Welcome back, {{ authStore.user?.name }}!
              </div>
            </template>
            <p class="welcome-message">
              You have successfully logged in to your account. 
              Your authentication token is securely stored and will be used for API requests.
            </p>
            
            <a-descriptions :column="1" bordered>
              <a-descriptions-item label="User ID">
                {{ authStore.user?.id }}
              </a-descriptions-item>
              <a-descriptions-item label="Email">
                {{ authStore.user?.email }}
              </a-descriptions-item>
              <a-descriptions-item label="Email Verified">
                <a-tag :color="authStore.user?.email_verified_at ? 'green' : 'orange'">
                  {{ authStore.user?.email_verified_at ? 'Verified' : 'Not Verified' }}
                </a-tag>
              </a-descriptions-item>
              <a-descriptions-item label="Account Created">
                {{ authStore.user?.email_verified_at || 'N/A' }}
              </a-descriptions-item>
            </a-descriptions>
          </a-card>
        </div>

        <div class="actions-section">
          <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="12" :md="8">
              <a-card hoverable class="action-card">
                <template #title>
                  <div class="action-title">
                    <ApiOutlined style="margin-right: 8px;" />
                    API Testing
                  </div>
                </template>
                <p>Test protected API endpoints with your authentication token.</p>
                <a-button type="primary" @click="testProtectedApi">
                  Test API
                </a-button>
              </a-card>
            </a-col>

            <a-col :xs="24" :sm="12" :md="8">
              <a-card hoverable class="action-card">
                <template #title>
                  <div class="action-title">
                    <UserOutlined style="margin-right: 8px;" />
                    Profile
                  </div>
                </template>
                <p>View and update your profile information.</p>
                <a-button type="default">
                  View Profile
                </a-button>
              </a-card>
            </a-col>

            <a-col :xs="24" :sm="12" :md="8">
              <a-card hoverable class="action-card">
                <template #title>
                  <div class="action-title">
                    <SettingOutlined style="margin-right: 8px;" />
                    Settings
                  </div>
                </template>
                <p>Manage your account settings and preferences.</p>
                <a-button type="default">
                  Settings
                </a-button>
              </a-card>
            </a-col>
          </a-row>
        </div>
      </a-layout-content>
    </a-layout>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { message } from 'ant-design-vue'
import { 
  UserOutlined, 
  DownOutlined, 
  SettingOutlined, 
  LogoutOutlined,
  ApiOutlined 
} from '@ant-design/icons-vue'
import { useAuthStore } from '@/stores/auth'
import axios from '@/utils/http'

const router = useRouter()
const authStore = useAuthStore()

// Handle logout
const handleLogout = async () => {
  try {
    await authStore.logout()
    message.success('Logged out successfully')
    router.push('/login')
  } catch (error) {
    console.error('Logout error:', error)
    message.error('Logout failed')
  }
}

// Test protected API endpoint
const testProtectedApi = async () => {
  try {
    const response = await axios.get('/api/auth/test')
    message.success('API test successful!')
    console.log('API Response:', response.data)
  } catch (error: any) {
    console.error('API test error:', error)
    if (error.response?.data?.message) {
      message.error(error.response.data.message)
    } else {
      message.error('API test failed')
    }
  }
}
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  background-color: #f0f2f5;
}

.header {
  background: #fff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  padding: 0;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 100%;
}

.logo h2 {
  margin: 0;
  color: #1890ff;
  font-weight: 600;
}

.user-button {
  color: #595959;
  font-weight: 500;
}

.content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 24px;
}

.welcome-section {
  margin-bottom: 24px;
}

.welcome-card {
  border-radius: 8px;
}

.card-title {
  display: flex;
  align-items: center;
  color: #1890ff;
  font-weight: 600;
}

.welcome-message {
  color: #595959;
  margin-bottom: 24px;
  font-size: 16px;
  line-height: 1.5;
}

.actions-section {
  margin-top: 24px;
}

.action-card {
  border-radius: 8px;
  height: 100%;
}

.action-title {
  display: flex;
  align-items: center;
  color: #1890ff;
  font-weight: 600;
}

:deep(.ant-descriptions-item-label) {
  font-weight: 600;
}

:deep(.ant-card-head-title) {
  font-size: 18px;
}

:deep(.ant-btn) {
  border-radius: 6px;
}
</style>
