import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove, getByPemilik } from '../services/hewanService'
import { getAll as getAllPemilik } from '../services/pemilikHewanService'

function Hewan() {
  const [data, setData] = useState([])
  const [pemilikList, setPemilikList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ id_pemilik: '', nama_hewan: '', jenis_hewan: '', jenis_kelamin: 'jantan', umur: '' })

  useEffect(() => {
    fetchData()
  }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const [hewanRes, pemilikRes] = await Promise.all([getAll(), getAllPemilik()])
      setData(hewanRes.data.data ?? hewanRes.data ?? [])
      setPemilikList(pemilikRes.data.data ?? pemilikRes.data ?? [])
    } catch {
      setError('Gagal memuat data')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ id_pemilik: '', nama_hewan: '', jenis_hewan: '', jenis_kelamin: 'jantan', umur: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setForm({
      id_pemilik: item.id_pemilik,
      nama_hewan: item.nama_hewan,
      jenis_hewan: item.jenis_hewan,
      jenis_kelamin: item.jenis_kelamin ?? 'jantan',
      umur: item.umur ?? '',
    })
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        await update(editData.id_hewan, form)
        setSuccess('Data hewan berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Data hewan berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus data hewan ini?')) return
    try {
      await remove(id)
      setSuccess('Data hewan berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-award"></i> Data Hewan</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Hewan
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
                  <th>Nama Hewan</th>
                  <th>Jenis</th>
                  <th>Kelamin</th>
                  <th>Umur</th>
                  <th>Pemilik</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="7" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data hewan</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_hewan}>
                      <td>{item.id_hewan}</td>
                      <td><strong>{item.nama_hewan}</strong></td>
                      <td>{item.jenis_hewan}</td>
                      <td>
                        <span className={`badge bg-${item.jenis_kelamin === 'jantan' ? 'primary' : 'danger'}`}>
                          <i className={`bi bi-gender-${item.jenis_kelamin === 'jantan' ? 'male' : 'female'}`}></i>{' '}
                          {item.jenis_kelamin ? item.jenis_kelamin.charAt(0).toUpperCase() + item.jenis_kelamin.slice(1) : '-'}
                        </span>
                      </td>
                      <td>{item.umur ? item.umur + ' tahun' : '-'}</td>
                      <td>{item.pemilik_hewan?.nama_pemilik || item.id_pemilik}</td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_hewan)}>
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
                  <h5 className="modal-title">{editData ? 'Edit Hewan' : 'Tambah Hewan'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Pemilik</label>
                    <select className="form-select" value={form.id_pemilik}
                      onChange={e => setForm({ ...form, id_pemilik: e.target.value })} required>
                      <option value="">-- Pilih Pemilik --</option>
                      {pemilikList.map(p => (
                        <option key={p.id_pemilik} value={p.id_pemilik}>{p.nama_pemilik}</option>
                      ))}
                    </select>
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Nama Hewan</label>
                    <input type="text" className="form-control" value={form.nama_hewan}
                      onChange={e => setForm({ ...form, nama_hewan: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Jenis Hewan</label>
                    <input type="text" className="form-control" value={form.jenis_hewan}
                      onChange={e => setForm({ ...form, jenis_hewan: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Jenis Kelamin</label>
                    <select className="form-select" value={form.jenis_kelamin}
                      onChange={e => setForm({ ...form, jenis_kelamin: e.target.value })} required>
                      <option value="jantan">Jantan</option>
                      <option value="betina">Betina</option>
                    </select>
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Umur</label>
                    <input type="text" className="form-control" value={form.umur}
                      onChange={e => setForm({ ...form, umur: e.target.value })} placeholder="cth: 2" />
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

export default Hewan
