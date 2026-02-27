import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/userService'

function Users() {
  const [users, setUsers] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ name: '', email: '', password: '', role: 'pegawai' })

  useEffect(() => {
    fetchUsers()
  }, [])

  async function fetchUsers() {
    try {
      setLoading(true)
      const res = await getAll()
      setUsers(res.data.data ?? res.data ?? [])
    } catch (err) {
      setError('Gagal memuat data users')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ name: '', email: '', password: '', role: 'pegawai' })
    setShowModal(true)
  }

  function openEdit(user) {
    setEditData(user)
    setForm({ name: user.name, email: user.email, password: '', role: user.role })
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        const payload = { ...form }
        if (!payload.password) delete payload.password
        await update(editData.id, payload)
        setSuccess('User berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('User berhasil ditambahkan')
      }
      setShowModal(false)
      fetchUsers()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus user ini?')) return
    try {
      await remove(id)
      setSuccess('User berhasil dihapus')
      fetchUsers()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus user')
    }
  }

  if (loading) return <Loading />

  return (
    <div className="p-4">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-people me-2"></i>Users</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-lg me-1"></i>Tambah User
        </button>
      </div>

      <Alert type="danger" message={error} onClose={() => setError('')} />
      <Alert type="success" message={success} onClose={() => setSuccess('')} />

      <div className="table-container">
        <table className="table table-hover align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {users.length === 0 ? (
              <tr><td colSpan="5" className="text-center text-muted">Tidak ada data</td></tr>
            ) : (
              users.map((user, i) => (
                <tr key={user.id}>
                  <td>{i + 1}</td>
                  <td>{user.name}</td>
                  <td>{user.email}</td>
                  <td>
                    <span className={`badge bg-${user.role === 'admin' ? 'danger' : user.role === 'dokter' ? 'primary' : 'secondary'}`}>
                      {user.role}
                    </span>
                  </td>
                  <td>
                    <button className="btn btn-sm btn-warning me-1" onClick={() => openEdit(user)}>
                      <i className="bi bi-pencil"></i>
                    </button>
                    <button className="btn btn-sm btn-danger" onClick={() => handleDelete(user.id)}>
                      <i className="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Modal */}
      {showModal && (
        <div className="modal fade show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <form onSubmit={handleSubmit}>
                <div className="modal-header">
                  <h5 className="modal-title">{editData ? 'Edit User' : 'Tambah User'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nama</label>
                    <input type="text" className="form-control" value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Email</label>
                    <input type="email" className="form-control" value={form.email} onChange={e => setForm({ ...form, email: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Password {editData && <small className="text-muted">(kosongkan jika tidak diubah)</small>}</label>
                    <input type="password" className="form-control" value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} {...(!editData && { required: true })} />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Role</label>
                    <select className="form-select" value={form.role} onChange={e => setForm({ ...form, role: e.target.value })}>
                      <option value="admin">Admin</option>
                      <option value="pegawai">Pegawai</option>
                      <option value="dokter">Dokter</option>
                    </select>
                  </div>
                </div>
                <div className="modal-footer">
                  <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Batal</button>
                  <button type="submit" className="btn btn-primary">{editData ? 'Simpan' : 'Tambah'}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default Users
