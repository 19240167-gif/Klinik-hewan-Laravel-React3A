import api from './api'

export const getAll = () => api.get('/pemeriksaan')
export const getPending = () => api.get('/pemeriksaan-pending')
export const getById = (id) => api.get(`/pemeriksaan/${id}`)
export const create = (data) => api.post('/pemeriksaan', data)
export const update = (id, data) => api.put(`/pemeriksaan/${id}`, data)
export const remove = (id) => api.delete(`/pemeriksaan/${id}`)
