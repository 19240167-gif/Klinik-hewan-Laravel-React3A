import api from './api'

export const getAll = () => api.get('/obat')
export const getById = (id) => api.get(`/obat/${id}`)
export const create = (data) => api.post('/obat', data)
export const update = (id, data) => api.put(`/obat/${id}`, data)
export const remove = (id) => api.delete(`/obat/${id}`)
