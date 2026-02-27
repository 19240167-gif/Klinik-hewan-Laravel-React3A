import api from './api'

export const getAll = () => api.get('/hewan')
export const getById = (id) => api.get(`/hewan/${id}`)
export const create = (data) => api.post('/hewan', data)
export const update = (id, data) => api.put(`/hewan/${id}`, data)
export const remove = (id) => api.delete(`/hewan/${id}`)
export const getByPemilik = (idPemilik) => api.get(`/hewan-by-pemilik/${idPemilik}`)
