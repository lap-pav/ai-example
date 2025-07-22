import { defineStore } from 'pinia'
import axios from 'axios'

export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
}

export interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  loading: boolean
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface AuthResponse {
  token: string
  user: User
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: localStorage.getItem('auth_token'),
    isAuthenticated: false,
    loading: false
  }),

  getters: {
    isLoggedIn: (state) => !!state.token && !!state.user,
    getCurrentUser: (state) => state.user
  },

  actions: {
    // Initialize auth state from localStorage
    async initAuth() {
      const token = localStorage.getItem('auth_token')
      if (token) {
        this.token = token
        this.setAuthHeader(token)
        try {
          await this.fetchUser()
        } catch (error) {
          // Token might be invalid, clear it
          this.logout()
        }
      }
    },

    // Set authentication header for axios
    setAuthHeader(token: string) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },

    // Clear authentication header
    clearAuthHeader() {
      delete axios.defaults.headers.common['Authorization']
    },

    // Login action
    async login(credentials: LoginCredentials): Promise<void> {
      this.loading = true
      try {
        const response = await axios.post<AuthResponse>('/api/auth/login', credentials)
        const { token, user } = response.data

        // Store token and user
        this.token = token
        this.user = user
        this.isAuthenticated = true

        // Persist token
        localStorage.setItem('auth_token', token)
        this.setAuthHeader(token)
      } catch (error) {
        this.logout()
        throw error
      } finally {
        this.loading = false
      }
    },

    // Register action
    async register(userData: RegisterData): Promise<void> {
      this.loading = true
      try {
        const response = await axios.post<AuthResponse>('/api/auth/register', userData)
        const { token, user } = response.data

        // Store token and user
        this.token = token
        this.user = user
        this.isAuthenticated = true

        // Persist token
        localStorage.setItem('auth_token', token)
        this.setAuthHeader(token)
      } catch (error) {
        this.logout()
        throw error
      } finally {
        this.loading = false
      }
    },

    // Logout action
    async logout(): Promise<void> {
      try {
        if (this.token) {
          await axios.post('/api/auth/logout')
        }
      } catch (error) {
        // Even if logout fails on server, clear local state
        console.error('Logout error:', error)
      } finally {
        // Clear local state
        this.token = null
        this.user = null
        this.isAuthenticated = false

        // Clear storage and headers
        localStorage.removeItem('auth_token')
        this.clearAuthHeader()
      }
    },

    // Fetch current user
    async fetchUser(): Promise<void> {
      try {
        const response = await axios.get<{ user: User }>('/api/auth/user')
        this.user = response.data.user
        this.isAuthenticated = true
      } catch (error) {
        this.logout()
        throw error
      }
    }
  }
})
