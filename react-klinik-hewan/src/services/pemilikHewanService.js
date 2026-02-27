import api from './api'

export const getAll = () => api.get('/pemilik-hewan')
export const getById = (id) => api.get(`/pemilik-hewan/${id}`)
export const create = (data) => api.post('/pemilik-hewan', data)
export const update = (id, data) => api.put(`/pemilik-hewan/${id}`, data)
export const remove = (id) => api.delete(`/pemilik-hewan/${id}`)
