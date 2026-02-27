import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/obatService'

function Obat() {
  const [data, setData] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [form, setForm] = useState({ nama_obat: '', jenis_obat: '', harga_obat: '', stok: '' })

  useEffect(() => {
    fetchData()
  }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const res = await getAll()
      setData(res.data.data ?? res.data ?? [])
    } catch {
      setError('Gagal memuat data obat')
    } finally {
      setLoading(false)
    }
  }

  function openCreate() {
    setEditData(null)
    setForm({ nama_obat: '', jenis_obat: '', harga_obat: '', stok: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setForm({
      nama_obat: item.nama_obat,
      jenis_obat: item.jenis_obat ?? '',
      harga_obat: item.harga_obat,
      stok: item.stok,
    })
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        await update(editData.id_obat, form)
        setSuccess('Data obat berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Data obat berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus data obat ini?')) return
    try {
      await remove(id)
      setSuccess('Data obat berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num)
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-capsule"></i> Data Obat</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Obat
        </button>
      </div>

      <Alert type="danger" message={error} onClose={() => setError('')} />
      <Alert type="success" message={success} onClose={() => setSuccess('')} />

      <div className="alert alert-info">
        <i className="bi bi-info-circle"></i> <strong>Info:</strong> Stok obat akan berkurang otomatis saat digunakan dalam pemeriksaan.{' '}
        <span className="badge bg-danger">Merah</span> = Stok habis,{' '}
        <span className="badge bg-warning text-dark">Kuning</span> = Stok &le; 10,{' '}
        <span className="badge bg-success">Hijau</span> = Stok cukup
      </div>

      <div className="card">
        <div className="card-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead className="table-light">
                <tr>
                  <th>ID Obat</th>
                  <th>Nama Obat</th>
                  <th>Jenis</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="6" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data obat</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_obat} className={item.stok <= 0 ? 'table-danger' : item.stok <= 10 ? 'table-warning' : ''}>
                      <td>{item.id_obat}</td>
                      <td>{item.nama_obat}</td>
                      <td>{item.jenis_obat || '-'}</td>
                      <td>Rp {Number(item.harga_obat).toLocaleString('id-ID')}</td>
                      <td>
                        {item.stok <= 0 ? (
                          <span className="badge bg-danger">Habis</span>
                        ) : item.stok <= 10 ? (
                          <span className="badge bg-warning text-dark">{item.stok}</span>
                        ) : (
                          <span className="badge bg-success">{item.stok}</span>
                        )}
                      </td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_obat)}>
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
                  <h5 className="modal-title">{editData ? 'Edit Obat' : 'Tambah Obat'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nama Obat</label>
                    <input type="text" className="form-control" value={form.nama_obat}
                      onChange={e => setForm({ ...form, nama_obat: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Jenis Obat</label>
                    <input type="text" className="form-control" value={form.jenis_obat}
                      onChange={e => setForm({ ...form, jenis_obat: e.target.value })} />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Harga Obat</label>
                    <input type="number" className="form-control" value={form.harga_obat}
                      onChange={e => setForm({ ...form, harga_obat: e.target.value })} required min="0" step="100" />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Stok</label>
                    <input type="number" className="form-control" value={form.stok}
                      onChange={e => setForm({ ...form, stok: e.target.value })} required min="0" />
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

export default Obat
