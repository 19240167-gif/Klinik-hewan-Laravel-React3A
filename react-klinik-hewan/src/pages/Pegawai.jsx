import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/pegawaiService'

function Pegawai() {
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ nama_pegawai: '', jenis_kelamin: 'laki-laki', no_telepon_pegawai: '', email: '', password: '' })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const res = await getAll()
      setData(res.data.data ?? res.data ?? [])
    } catch {
      setError('Gagal memuat data pegawai')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ nama_pegawai: '', jenis_kelamin: 'laki-laki', no_telepon_pegawai: '', email: '', password: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setForm({
      nama_pegawai: item.nama_pegawai,
      jenis_kelamin: item.jenis_kelamin ?? 'laki-laki',
      no_telepon_pegawai: item.no_telepon_pegawai ?? '',
      email: '',
      password: '',
    })
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        const { email, password, ...payload } = form
        await update(editData.id_pegawai, payload)
        setSuccess('Data pegawai berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Data pegawai berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus data pegawai ini?')) return
    try {
      await remove(id)
      setSuccess('Data pegawai berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-person-badge"></i> Data Pegawai</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Pegawai
        </button>
      </div>

      <Alert type="danger" message={error} onClose={() => setError('')} />
      <Alert type="success" message={success} onClose={() => setSuccess('')} />

      <div className="card">
        <div className="card-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead className="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nama Pegawai</th>
                  <th>Jenis Kelamin</th>
                  <th>No. Telepon</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="5" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data pegawai</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_pegawai}>
                      <td>{item.id_pegawai}</td>
                      <td><strong>{item.nama_pegawai}</strong></td>
                      <td>
                        <span className={`badge bg-${item.jenis_kelamin === 'laki-laki' ? 'primary' : 'danger'}`}>
                          {item.jenis_kelamin ? item.jenis_kelamin.charAt(0).toUpperCase() + item.jenis_kelamin.slice(1) : '-'}
                        </span>
                      </td>
                      <td>{item.no_telepon_pegawai || '-'}</td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_pegawai)}>
                            <i className="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {showModal && (
        <div className="modal fade show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <form onSubmit={handleSubmit}>
                <div className="modal-header">
                  <h5 className="modal-title">{editData ? 'Edit Pegawai' : 'Tambah Pegawai'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nama Pegawai</label>
                    <input type="text" className="form-control" value={form.nama_pegawai}
                      onChange={e => setForm({ ...form, nama_pegawai: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Jenis Kelamin</label>
                    <select className="form-select" value={form.jenis_kelamin}
                      onChange={e => setForm({ ...form, jenis_kelamin: e.target.value })} required>
                      <option value="laki-laki">Laki-laki</option>
                      <option value="perempuan">Perempuan</option>
                    </select>
                  </div>
                  <div className="mb-3">
                    <label className="form-label">No. Telepon</label>
                    <input type="text" className="form-control" value={form.no_telepon_pegawai}
                      onChange={e => setForm({ ...form, no_telepon_pegawai: e.target.value })} />
                  </div>
                  {!editData && (
                    <>
                      <hr />
                      <p className="text-muted small">Akun login untuk pegawai</p>
                      <div className="mb-3">
                        <label className="form-label">Email</label>
                        <input type="email" className="form-control" value={form.email}
                          onChange={e => setForm({ ...form, email: e.target.value })} required />
                      </div>
                      <div className="mb-3">
                        <label className="form-label">Password</label>
                        <input type="password" className="form-control" value={form.password}
                          onChange={e => setForm({ ...form, password: e.target.value })} required minLength={6} />
                      </div>
                    </>
                  )}
                </div>
                <div className="modal-footer">
                  <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Batal</button>
                  <button type="submit" className="btn btn-primary">{editData ? 'Update' : 'Simpan'}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </>
  )
}

export default Pegawai
