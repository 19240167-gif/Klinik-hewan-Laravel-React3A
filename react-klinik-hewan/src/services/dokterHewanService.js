import api from './api'

export const getAll = () => api.get('/dokter-hewan')
export const getById = (id) => api.get(`/dokter-hewan/${id}`)
export const create = (data) => api.post('/dokter-hewan', data)
export const update = (id, data) => api.put(`/dokter-hewan/${id}`, data)
export const remove = (id) => api.delete(`/dokter-hewan/${id}`)
