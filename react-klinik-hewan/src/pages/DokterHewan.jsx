import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/dokterHewanService'

function DokterHewan() {
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ nama_dokter: '', no_sip: '', email: '', password: '' })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const res = await getAll()
      setData(res.data.data ?? res.data ?? [])
    } catch {
      setError('Gagal memuat data dokter hewan')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ nama_dokter: '', no_sip: '', email: '', password: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setForm({
      nama_dokter: item.nama_dokter,
      no_sip: item.no_sip ?? '',
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
        await update(editData.id_dokter, payload)
        setSuccess('Data dokter hewan berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Data dokter hewan berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus data dokter ini?')) return
    try {
      await remove(id)
      setSuccess('Data dokter hewan berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-heart-pulse"></i> Data Dokter Hewan</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Dokter
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
                  <th>Nama Dokter</th>
                  <th>No. SIP</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="4" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data dokter hewan</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_dokter}>
                      <td>{item.id_dokter}</td>
                      <td><strong>{item.nama_dokter}</strong></td>
                      <td>{item.no_sip || '-'}</td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_dokter)}>
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
                  <h5 className="modal-title">{editData ? 'Edit Dokter Hewan' : 'Tambah Dokter Hewan'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nama Dokter</label>
                    <input type="text" className="form-control" value={form.nama_dokter}
                      onChange={e => setForm({ ...form, nama_dokter: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">No. SIP</label>
                    <input type="text" className="form-control" value={form.no_sip}
                      onChange={e => setForm({ ...form, no_sip: e.target.value })} />
                  </div>
                  {!editData && (
                    <>
                      <hr />
                      <p className="text-muted small">Akun login untuk dokter</p>
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

export default DokterHewan
