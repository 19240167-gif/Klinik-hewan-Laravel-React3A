import api from './api'

export const getAll = () => api.get('/pembayaran')
export const getPending = () => api.get('/pembayaran-pending')
export const getById = (id) => api.get(`/pembayaran/${id}`)
export const create = (data) => api.post('/pembayaran', data)
export const remove = (id) => api.delete(`/pembayaran/${id}`)
