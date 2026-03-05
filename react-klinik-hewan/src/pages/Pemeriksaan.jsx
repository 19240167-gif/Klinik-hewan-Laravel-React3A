import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, getPending, create, remove } from '../services/pemeriksaanService'
import { getAll as getAllDokter } from '../services/dokterHewanService'
import { getAll as getAllObat } from '../services/obatService'

function Pemeriksaan() {
  const [riwayat, setRiwayat] = useState([])
  const [pendingList, setPendingList] = useState([])
  const [dokterList, setDokterList] = useState([])
  const [obatList, setObatList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [selectedPendaftaran, setSelectedPendaftaran] = useState(null)
  const [tab, setTab] = useState('pending') // 'pending' | 'riwayat'
  const [form, setForm] = useState({
    id_pendaftaran: '', id_dokter: '', tanggal_periksa: '', diagnosa: '', tindakan: '', biaya_tindakan: 0, obat: []
  })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const [riwayatRes, pendingRes, dokterRes, obatRes] = await Promise.all([
        getAll(), getPending(), getAllDokter(), getAllObat()
      ])
      setRiwayat(riwayatRes.data.data ?? riwayatRes.data ?? [])
      setPendingList(pendingRes.data.data ?? pendingRes.data ?? [])
      setDokterList(dokterRes.data.data ?? dokterRes.data ?? [])
      setObatList(obatRes.data.data ?? obatRes.data ?? [])
    } catch {
      setError('Gagal memuat data')
    } finally {
      setLoading(false)
    }
  }

  function openCreate(pendaftaran) {
    const user = JSON.parse(localStorage.getItem('user') || '{}')
    // Auto-select dokter if current user is dokter
    let defaultDokter = ''
    if (user.role === 'dokter') {
      const found = dokterList.find(d => d.nama_dokter === user.name)
      if (found) defaultDokter = found.id_dokter
    }

    setSelectedPendaftaran(pendaftaran)
    setForm({
      id_pendaftaran: pendaftaran.id_pendaftaran,
      id_dokter: defaultDokter,
      tanggal_periksa: new Date().toISOString().split('T')[0],
      diagnosa: '',
      tindakan: '',
      biaya_tindakan: 0,
      obat: [],
    })
    setShowModal(true)
  }

  function addObatRow() {
    setForm({ ...form, obat: [...form.obat, { id_obat: '', jumlah: 1 }] })
  }

  function removeObatRow(index) {
    const newObat = form.obat.filter((_, i) => i !== index)
    setForm({ ...form, obat: newObat })
  }

  function updateObatRow(index, field, value) {
    const newObat = [...form.obat]
    newObat[index] = { ...newObat[index], [field]: value }
    setForm({ ...form, obat: newObat })
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      const payload = { ...form }
      // Filter empty obat rows
      payload.obat = payload.obat.filter(o => o.id_obat && o.jumlah > 0)
      await create(payload)
      setSuccess('Pemeriksaan berhasil ditambahkan')
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus pemeriksaan ini? Stok obat akan dikembalikan.')) return
    try {
      await remove(id)
      setSuccess('Pemeriksaan berhasil dihapus')
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal menghapus data')
    }
  }

  function formatRupiah(num) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num || 0)
  }

  if (loading) return <Loading />

  return (
    <>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2><i className="bi bi-clipboard2-pulse"></i> Pemeriksaan</h2>
      </div>

      <Alert type="danger" message={error} onClose={() => setError('')} />
      <Alert type="success" message={success} onClose={() => setSuccess('')} />

      {/* Tab navigation */}
      <ul className="nav nav-tabs mb-3">
        <li className="nav-item">
          <button className={`nav-link ${tab === 'pending' ? 'active' : ''}`} onClick={() => setTab('pending')}>
            <i className="bi bi-hourglass-split"></i> Antrian Menunggu
            {pendingList.length > 0 && <span className="badge bg-danger ms-1">{pendingList.length}</span>}
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${tab === 'riwayat' ? 'active' : ''}`} onClick={() => setTab('riwayat')}>
            <i className="bi bi-clock-history"></i> Riwayat Pemeriksaan
          </button>
        </li>
      </ul>

      {/* Tab: Pending */}
      {tab === 'pending' && (
        <div className="card">
          <div className="card-header bg-warning text-dark">
            <h5 className="mb-0"><i className="bi bi-hourglass-split"></i> Pendaftaran Menunggu Pemeriksaan</h5>
          </div>
          <div className="card-body">
            <div className="table-responsive">
              <table className="table table-hover">
                <thead className="table-light">
                  <tr>
                    <th>ID Daftar</th>
                    <th>Tanggal</th>
                    <th>Pemilik</th>
                    <th>Hewan</th>
                    <th>Keluhan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {pendingList.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="text-center py-4">
                        <i className="bi bi-check-circle fs-1 text-success"></i>
                        <p className="text-muted">Tidak ada antrian menunggu</p>
                      </td>
                    </tr>
                  ) : (
                    pendingList.map((item) => (
                      <tr key={item.id_pendaftaran}>
                        <td>{item.id_pendaftaran}</td>
                        <td>{item.tanggal_daftar ? new Date(item.tanggal_daftar).toLocaleDateString('id-ID') : '-'}</td>
                        <td>{item.pemilik_hewan?.nama_pemilik || '-'}</td>
                        <td>{item.hewan?.nama_hewan || '-'} <small className="text-muted">({item.hewan?.jenis_hewan || ''})</small></td>
                        <td>{item.keluhan ? (item.keluhan.length > 40 ? item.keluhan.substring(0, 40) + '...' : item.keluhan) : '-'}</td>
                        <td>
                          <button className="btn btn-sm btn-success" onClick={() => openCreate(item)}>
                            <i className="bi bi-clipboard2-pulse"></i> Periksa
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Tab: Riwayat */}
      {tab === 'riwayat' && (
        <div className="card">
          <div className="card-header">
            <h5 className="mb-0"><i className="bi bi-clock-history"></i> Riwayat Pemeriksaan</h5>
          </div>
          <div className="card-body">
            <div className="table-responsive">
              <table className="table table-hover">
                <thead className="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Tanggal Periksa</th>
                    <th>Pemilik</th>
                    <th>Hewan</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Biaya</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {riwayat.length === 0 ? (
                    <tr>
                      <td colSpan="9" className="text-center py-4">
                        <i className="bi bi-inbox fs-1 text-muted"></i>
                        <p className="text-muted">Belum ada riwayat pemeriksaan</p>
                      </td>
                    </tr>
                  ) : (
                    riwayat.map((item) => (
                      <tr key={item.id_pemeriksaan}>
                        <td>{item.id_pemeriksaan}</td>
                        <td>{item.tanggal_periksa ? new Date(item.tanggal_periksa).toLocaleDateString('id-ID') : '-'}</td>
                        <td>{item.pendaftaran?.pemilik_hewan?.nama_pemilik || '-'}</td>
                        <td>{item.pendaftaran?.hewan?.nama_hewan || '-'}</td>
                        <td>{item.dokter_hewan?.nama_dokter || '-'}</td>
                        <td>{item.diagnosa ? (item.diagnosa.length > 30 ? item.diagnosa.substring(0, 30) + '...' : item.diagnosa) : '-'}</td>
                        <td>{formatRupiah(item.biaya_tindakan)}</td>
                        <td>
                          {item.pembayaran
                            ? <span className="badge bg-success">Lunas</span>
                            : <span className="badge bg-danger">Belum</span>
                          }
                        </td>
                        <td>
                          {!item.pembayaran && (
                            <button className="btn btn-sm btn-danger" title="Hapus" onClick={() => handleDelete(item.id_pemeriksaan)}>
                              <i className="bi bi-trash"></i>
                            </button>
                          )}
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Modal Pemeriksaan */}
      {showModal && selectedPendaftaran && (
        <div className="modal fade show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog modal-lg">
            <div className="modal-content">
              <form onSubmit={handleSubmit}>
                <div className="modal-header bg-success text-white">
                  <h5 className="modal-title"><i className="bi bi-clipboard2-pulse"></i> Form Pemeriksaan</h5>
                  <button type="button" className="btn-close btn-close-white" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  {/* Info Pendaftaran */}
                  <div className="alert alert-info">
                    <strong>Pendaftaran:</strong> {selectedPendaftaran.id_pendaftaran} |
                    <strong> Pemilik:</strong> {selectedPendaftaran.pemilik_hewan?.nama_pemilik || '-'} |
                    <strong> Hewan:</strong> {selectedPendaftaran.hewan?.nama_hewan || '-'} ({selectedPendaftaran.hewan?.jenis_hewan || ''}) |
                    <strong> Keluhan:</strong> {selectedPendaftaran.keluhan || '-'}
                  </div>

                  <div className="row">
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Dokter Hewan</label>
                      <select className="form-select" value={form.id_dokter}
                        onChange={e => setForm({ ...form, id_dokter: e.target.value })}
                        disabled={JSON.parse(localStorage.getItem('user') || '{}').role === 'dokter' && form.id_dokter !== ''}
                        required>
                        <option value="">-- Pilih Dokter --</option>
                        {dokterList.map(d => (
                          <option key={d.id_dokter} value={d.id_dokter}>{d.nama_dokter}</option>
                        ))}
                      </select>
                      {JSON.parse(localStorage.getItem('user') || '{}').role === 'dokter' && (
                        <small className="text-muted">Otomatis terisi sesuai akun dokter Anda</small>
                      )}
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Tanggal Periksa</label>
                      <input type="date" className="form-control" value={form.tanggal_periksa}
                        onChange={e => setForm({ ...form, tanggal_periksa: e.target.value })} required />
                    </div>
                  </div>

                  <div className="mb-3">
                    <label className="form-label">Diagnosa</label>
                    <textarea className="form-control" rows="2" value={form.diagnosa}
                      onChange={e => setForm({ ...form, diagnosa: e.target.value })}></textarea>
                  </div>

                  <div className="mb-3">
                    <label className="form-label">Tindakan</label>
                    <textarea className="form-control" rows="2" value={form.tindakan}
                      onChange={e => setForm({ ...form, tindakan: e.target.value })}></textarea>
                  </div>

                  <div className="mb-3">
                    <label className="form-label">Biaya Tindakan (Rp)</label>
                    <input type="number" className="form-control" value={form.biaya_tindakan}
                      onChange={e => setForm({ ...form, biaya_tindakan: parseInt(e.target.value) || 0 })} min="0" required />
                  </div>

                  {/* Obat section */}
                  <hr />
                  <div className="d-flex justify-content-between align-items-center mb-2">
                    <h6 className="mb-0"><i className="bi bi-capsule"></i> Obat yang Diberikan</h6>
                    <button type="button" className="btn btn-sm btn-outline-primary" onClick={addObatRow}>
                      <i className="bi bi-plus"></i> Tambah Obat
                    </button>
                  </div>

                  {form.obat.length === 0 && (
                    <p className="text-muted small">Belum ada obat ditambahkan (opsional)</p>
                  )}

                  {form.obat.map((item, index) => (
                    <div className="row mb-2 align-items-end" key={index}>
                      <div className="col-md-6">
                        <select className="form-select form-select-sm" value={item.id_obat}
                          onChange={e => updateObatRow(index, 'id_obat', e.target.value)} required>
                          <option value="">-- Pilih Obat --</option>
                          {obatList.filter(o => o.stok > 0).map(o => (
                            <option key={o.id_obat} value={o.id_obat}>
                              {o.nama_obat} (Stok: {o.stok}) - Rp {Number(o.harga_obat).toLocaleString('id-ID')}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="col-md-3">
                        <input type="number" className="form-control form-control-sm" placeholder="Jumlah"
                          value={item.jumlah} onChange={e => updateObatRow(index, 'jumlah', parseInt(e.target.value) || 1)} min="1" required />
                      </div>
                      <div className="col-md-3">
                        <button type="button" className="btn btn-sm btn-outline-danger" onClick={() => removeObatRow(index)}>
                          <i className="bi bi-trash"></i> Hapus
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
                <div className="modal-footer">
                  <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Batal</button>
                  <button type="submit" className="btn btn-success">
                    <i className="bi bi-check-circle"></i> Simpan Pemeriksaan
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </>
  )
}

export default Pemeriksaan
