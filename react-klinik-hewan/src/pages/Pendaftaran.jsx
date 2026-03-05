import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, create, update, remove } from '../services/pendaftaranService'
import { getAll as getAllPemilik } from '../services/pemilikHewanService'
import { getByPemilik } from '../services/hewanService'

function Pendaftaran() {
  const [data, setData] = useState([])
  const [pemilikList, setPemilikList] = useState([])
  const [hewanList, setHewanList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [editData, setEditData] = useState(null)
  const [selectedPemilik, setSelectedPemilik] = useState('')
  const [form, setForm] = useState({ id_hewan: '', tanggal_daftar: '', keluhan: '' })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const [pendaftaranRes, pemilikRes] = await Promise.all([getAll(), getAllPemilik()])
      setData(pendaftaranRes.data.data ?? pendaftaranRes.data ?? [])
      setPemilikList(pemilikRes.data.data ?? pemilikRes.data ?? [])
    } catch {
      setError('Gagal memuat data')
    } finally {
      setLoading(false)
    }
  }

  async function handlePemilikChange(idPemilik) {
    setSelectedPemilik(idPemilik)
    setForm({ ...form, id_hewan: '' })
    if (idPemilik) {
      try {
        const res = await getByPemilik(idPemilik)
        setHewanList(res.data.data ?? res.data ?? [])
      } catch {
        setHewanList([])
      }
    } else {
      setHewanList([])
    }
  }

  function openCreate() {
    setEditData(null)
    setSelectedPemilik('')
    setHewanList([])
    setForm({ id_hewan: '', tanggal_daftar: new Date().toISOString().split('T')[0], keluhan: '' })
    setShowModal(true)
  }

  function openEdit(item) {
    setEditData(item)
    setSelectedPemilik(item.hewan?.id_pemilik || '')
    setForm({
      id_hewan: item.id_hewan,
      tanggal_daftar: item.tanggal_daftar ? item.tanggal_daftar.split('T')[0] : '',
      keluhan: item.keluhan ?? '',
    })
    // Load hewan list for the pemilik
    if (item.hewan?.id_pemilik) {
      getByPemilik(item.hewan.id_pemilik).then(res => {
        setHewanList(res.data.data ?? res.data ?? [])
      }).catch(() => setHewanList([]))
    }
    setShowModal(true)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      if (editData) {
        await update(editData.id_pendaftaran, form)
        setSuccess('Pendaftaran berhasil diperbarui')
      } else {
        await create(form)
        setSuccess('Pendaftaran berhasil ditambahkan')
      }
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus pendaftaran ini?')) return
    try {
      await remove(id)
      setSuccess('Pendaftaran berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  function getStatusBadge(status) {
    switch (status) {
      case 'menunggu': return <span className="badge bg-warning text-dark">Menunggu</span>
      case 'selesai': return <span className="badge bg-success">Selesai</span>
      default: return <span className="badge bg-secondary">{status || '-'}</span>
    }
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-clipboard-plus"></i> Data Pendaftaran</h2>
        <button className="btn btn-primary" onClick={openCreate}>
          <i className="bi bi-plus-circle"></i> Tambah Pendaftaran
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
                  <th>Tanggal</th>
                  <th>Pemilik</th>
                  <th>Hewan</th>
                  <th>Keluhan</th>
                  <th>Pegawai</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {data.length === 0 ? (
                  <tr>
                    <td colSpan="8" className="text-center py-4">
                      <i className="bi bi-inbox fs-1 text-muted"></i>
                      <p className="text-muted">Belum ada data pendaftaran</p>
                    </td>
                  </tr>
                ) : (
                  data.map((item) => (
                    <tr key={item.id_pendaftaran}>
                      <td>{item.id_pendaftaran}</td>
                      <td>{item.tanggal_daftar ? new Date(item.tanggal_daftar).toLocaleDateString('id-ID') : '-'}</td>
                      <td>{item.pemilik_hewan?.nama_pemilik || '-'}</td>
                      <td>{item.hewan?.nama_hewan || '-'} <small className="text-muted">({item.hewan?.jenis_hewan || ''})</small></td>
                      <td>{item.keluhan ? (item.keluhan.length > 40 ? item.keluhan.substring(0, 40) + '...' : item.keluhan) : '-'}</td>
                      <td>{item.pegawai?.nama_pegawai || '-'}</td>
                      <td>{getStatusBadge(item.status)}</td>
                      <td>
                        <div className="btn-group btn-group-sm">
                          <button className="btn btn-warning" title="Edit" onClick={() => openEdit(item)}>
                            <i className="bi bi-pencil"></i>
                          </button>
                          <button className="btn btn-danger" title="Hapus" onClick={() => handleDelete(item.id_pendaftaran)}>
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
                  <h5 className="modal-title">{editData ? 'Edit Pendaftaran' : 'Tambah Pendaftaran'}</h5>
                  <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Pemilik Hewan</label>
                    <select className="form-select" value={selectedPemilik}
                      onChange={e => handlePemilikChange(e.target.value)} required>
                      <option value="">-- Pilih Pemilik --</option>
                      {pemilikList.map(p => (
                        <option key={p.id_pemilik} value={p.id_pemilik}>{p.nama_pemilik}</option>
                      ))}
                    </select>
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Hewan</label>
                    <select className="form-select" value={form.id_hewan}
                      onChange={e => setForm({ ...form, id_hewan: e.target.value })} required>
                      <option value="">-- Pilih Hewan --</option>
                      {hewanList.map(h => (
                        <option key={h.id_hewan} value={h.id_hewan}>{h.nama_hewan} ({h.jenis_hewan})</option>
                      ))}
                    </select>
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Tanggal Daftar</label>
                    <input type="date" className="form-control" value={form.tanggal_daftar}
                      onChange={e => setForm({ ...form, tanggal_daftar: e.target.value })} required />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Keluhan</label>
                    <textarea className="form-control" rows="3" value={form.keluhan}
                      onChange={e => setForm({ ...form, keluhan: e.target.value })}></textarea>
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

export default Pendaftaran
