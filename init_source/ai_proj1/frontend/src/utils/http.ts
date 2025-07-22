import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

// Set base URL for API
axios.defaults.baseURL = 'http://localhost:9001'

// Request interceptor to add auth token
axios.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore()
    const token = authStore.token || localStorage.getItem('auth_token')
    
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor to handle authentication errors
axios.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    const authStore = useAuthStore()
    
    // Handle 401 Unauthorized responses
    if (error.response?.status === 401) {
      // Clear auth state and redirect to login
      authStore.logout()
      
      // Redirect to login page if not already there
      if (window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }
    
    return Promise.reject(error)
  }
)

export default axios
