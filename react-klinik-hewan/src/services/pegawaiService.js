import api from './api'

export const getAll = () => api.get('/pegawai')
export const getById = (id) => api.get(`/pegawai/${id}`)
export const create = (data) => api.post('/pegawai', data)
export const update = (id, data) => api.put(`/pegawai/${id}`, data)
export const remove = (id) => api.delete(`/pegawai/${id}`)
