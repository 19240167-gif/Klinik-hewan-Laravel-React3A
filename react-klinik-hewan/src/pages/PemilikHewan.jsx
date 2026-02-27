import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/pemilikHewanService'

function PemilikHewan() {
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ nama_pemilik: '', alamat: '', no_tlp: '' })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const res = await getAll()
      setData(res.data.data ?? res.data ?? [])
    } catch {
      setError('Gagal memuat data pemilik hewan')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ nama_pemilik: '', alamat: '', no_tlp: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setForm({ nama_pemilik: item.nama_pemilik, alamat: item.alamat ?? '', no_tlp: item.no_tlp ?? '' })
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        await update(editData.id_pemilik, form)
        setSuccess('Data pemilik berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Data pemilik berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus data ini?')) return
    try {
      await remove(id)
      setSuccess('Data pemilik berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-people"></i> Data Pemilik Hewan</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Pemilik Hewan
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
                  <th>Nama Pemilik</th>
                  <th>No. Telepon</th>
                  <th>Alamat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="5" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data pemilik hewan</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_pemilik}>
                      <td>{item.id_pemilik}</td>
                      <td>{item.nama_pemilik}</td>
                      <td>{item.no_tlp || '-'}</td>
                      <td>{item.alamat ? (item.alamat.length > 50 ? item.alamat.substring(0, 50) + '...' : item.alamat) : '-'}</td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_pemilik)}>
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

      {/* Modal Tambah/Edit */}
      {showModal && (
        <div className="modal fade show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <form onSubmit={handleSubmit}>
                <div className="modal-header">
                  <h5 className="modal-title">{editData ? 'Edit Pemilik Hewan' : 'Tambah Pemilik Hewan'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nama Pemilik</label>
                    <input type="text" className="form-control" value={form.nama_pemilik}
                      onChange={e => setForm({ ...form, nama_pemilik: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">No. Telepon</label>
                    <input type="text" className="form-control" value={form.no_tlp}
                      onChange={e => setForm({ ...form, no_tlp: e.target.value })} />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Alamat</label>
                    <textarea className="form-control" rows="3" value={form.alamat}
                      onChange={e => setForm({ ...form, alamat: e.target.value })}></textarea>
                  </div>
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

export default PemilikHewan
