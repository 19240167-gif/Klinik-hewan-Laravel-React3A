import api from './api'

export const getAll = () => api.get('/pendaftaran')
export const getById = (id) => api.get(`/pendaftaran/${id}`)
export const create = (data) => api.post('/pendaftaran', data)
export const update = (id, data) => api.put(`/pendaftaran/${id}`, data)
export const remove = (id) => api.delete(`/pendaftaran/${id}`)
