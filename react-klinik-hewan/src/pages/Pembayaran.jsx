import { useState, useEffect } from 'react'
import Loading from '../components/Loading'
import Alert from '../components/Alert'
import { getAll, getPending, create, remove } from '../services/pembayaranService'

function Pembayaran() {
  const [data, setData] = useState([])
  const [pendingList, setPendingList] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')
  const [showModal, setShowModal] = useState(false)
  const [selectedPemeriksaan, setSelectedPemeriksaan] = useState(null)
  const [tab, setTab] = useState('pending') // 'pending' | 'riwayat'
  const [form, setForm] = useState({ id_pemeriksaan: '', metode_bayar: 'tunai' })

  useEffect(() => { fetchData() }, [])

  async function fetchData() {
    try {
      setLoading(true)
      const [bayarRes, pendingRes] = await Promise.all([getAll(), getPending()])
      setData(bayarRes.data.data ?? bayarRes.data ?? [])
      setPendingList(pendingRes.data.data ?? pendingRes.data ?? [])
    } catch {
      setError('Gagal memuat data')
    } finally {
      setLoading(false)
    }
  }

  function openCreate(pemeriksaan) {
    setSelectedPemeriksaan(pemeriksaan)
    setForm({ id_pemeriksaan: pemeriksaan.id_pemeriksaan, metode_bayar: 'tunai' })
    setShowModal(true)
  }

  function calculateTotal(pemeriksaan) {
    const biayaTindakan = pemeriksaan.biaya_tindakan || 0
    let biayaObat = 0
    if (pemeriksaan.obats) {
      pemeriksaan.obats.forEach(o => {
        biayaObat += (o.harga_obat || 0) * (o.pivot?.jumlah || 0)
      })
    }
    return { biayaTindakan, biayaObat, total: biayaTindakan + biayaObat }
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    try {
      await create(form)
      setSuccess('Pembayaran berhasil diproses')
      setShowModal(false)
      fetchData()
    } catch (err) {
      setError(err.response?.data?.message || 'Terjadi kesalahan')
    }
  }

  async function handleDelete(id) {
    if (!window.confirm('Yakin ingin menghapus pembayaran ini?')) return
    try {
      await remove(id)
      setSuccess('Pembayaran berhasil dihapus')
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
        <h2><i className="bi bi-cash-stack"></i> Pembayaran</h2>
      </div>

      <Alert type="danger" message={error} onClose={() => setError('')} />
      <Alert type="success" message={success} onClose={() => setSuccess('')} />

      {/* Tab navigation */}
      <ul className="nav nav-tabs mb-3">
        <li className="nav-item">
          <button className={`nav-link ${tab === 'pending' ? 'active' : ''}`} onClick={() => setTab('pending')}>
            <i className="bi bi-hourglass-split"></i> Belum Dibayar
            {pendingList.length > 0 && <span className="badge bg-danger ms-1">{pendingList.length}</span>}
          </button>
        </li>
        <li className="nav-item">
          <button className={`nav-link ${tab === 'riwayat' ? 'active' : ''}`} onClick={() => setTab('riwayat')}>
            <i className="bi bi-clock-history"></i> Riwayat Pembayaran
          </button>
        </li>
      </ul>

      {/* Tab: Pending */}
      {tab === 'pending' && (
        <div className="card">
          <div className="card-header bg-warning text-dark">
            <h5 className="mb-0"><i className="bi bi-hourglass-split"></i> Pemeriksaan Belum Dibayar</h5>
          </div>
          <div className="card-body">
            <div className="table-responsive">
              <table className="table table-hover">
                <thead className="table-light">
                  <tr>
                    <th>ID Periksa</th>
                    <th>Tanggal</th>
                    <th>Pemilik</th>
                    <th>Hewan</th>
                    <th>Dokter</th>
                    <th>Biaya Tindakan</th>
                    <th>Biaya Obat</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {pendingList.length === 0 ? (
                    <tr>
                      <td colSpan="9" className="text-center py-4">
                        <i className="bi bi-check-circle fs-1 text-success"></i>
                        <p className="text-muted">Semua pemeriksaan sudah dibayar</p>
                      </td>
                    </tr>
                  ) : (
                    pendingList.map((item) => {
                      const calc = calculateTotal(item)
                      return (
                        <tr key={item.id_pemeriksaan}>
                          <td>{item.id_pemeriksaan}</td>
                          <td>{item.tanggal_periksa ? new Date(item.tanggal_periksa).toLocaleDateString('id-ID') : '-'}</td>
                          <td>{item.pendaftaran?.pemilik_hewan?.nama_pemilik || '-'}</td>
                          <td>{item.pendaftaran?.hewan?.nama_hewan || '-'}</td>
                          <td>{item.dokter_hewan?.nama_dokter || '-'}</td>
                          <td>{formatRupiah(calc.biayaTindakan)}</td>
                          <td>{formatRupiah(calc.biayaObat)}</td>
                          <td><strong>{formatRupiah(calc.total)}</strong></td>
                          <td>
                            <button className="btn btn-sm btn-success" onClick={() => openCreate(item)}>
                              <i className="bi bi-cash"></i> Bayar
                            </button>
                          </td>
                        </tr>
                      )
                    })
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
            <h5 className="mb-0"><i className="bi bi-clock-history"></i> Riwayat Pembayaran</h5>
          </div>
          <div className="card-body">
            <div className="table-responsive">
              <table className="table table-hover">
                <thead className="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Tanggal Bayar</th>
                    <th>Pemilik</th>
                    <th>Hewan</th>
                    <th>Metode</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  {data.length === 0 ? (
                    <tr>
                      <td colSpan="7" className="text-center py-4">
                        <i className="bi bi-inbox fs-1 text-muted"></i>
                        <p className="text-muted">Belum ada riwayat pembayaran</p>
                      </td>
                    </tr>
                  ) : (
                    data.map((item) => (
                      <tr key={item.id_pembayaran}>
                        <td>{item.id_pembayaran}</td>
                        <td>{item.tanggal_bayar ? new Date(item.tanggal_bayar).toLocaleDateString('id-ID') : '-'}</td>
                        <td>{item.pemeriksaan?.pendaftaran?.pemilik_hewan?.nama_pemilik || '-'}</td>
                        <td>{item.pemeriksaan?.pendaftaran?.hewan?.nama_hewan || '-'}</td>
                        <td><span className="badge bg-info">{item.metode_bayar || '-'}</span></td>
                        <td><strong>{formatRupiah(item.total_bayar)}</strong></td>
                        <td>
                          <button className="btn btn-sm btn-danger" title="Hapus" onClick={() => handleDelete(item.id_pembayaran)}>
                            <i className="bi bi-trash"></i>
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

      {/* Modal Pembayaran */}
      {showModal && selectedPemeriksaan && (
        <div className="modal fade show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <form onSubmit={handleSubmit}>
                <div className="modal-header bg-success text-white">
                  <h5 className="modal-title"><i className="bi bi-cash-stack"></i> Proses Pembayaran</h5>
                  <button type="button" className="btn-close btn-close-white" onClick={() => setShowModal(false)}></button>
                </div>
                <div className="modal-body">
                  {/* Info Pemeriksaan */}
                  <div className="alert alert-info">
                    <strong>ID Periksa:</strong> {selectedPemeriksaan.id_pemeriksaan}<br />
                    <strong>Pemilik:</strong> {selectedPemeriksaan.pendaftaran?.pemilik_hewan?.nama_pemilik || '-'}<br />
                    <strong>Hewan:</strong> {selectedPemeriksaan.pendaftaran?.hewan?.nama_hewan || '-'}<br />
                    <strong>Dokter:</strong> {selectedPemeriksaan.dokter_hewan?.nama_dokter || '-'}
                  </div>

                  {/* Rincian biaya */}
                  <div className="card mb-3">
                    <div className="card-header"><strong>Rincian Biaya</strong></div>
                    <div className="card-body">
                      <table className="table table-sm mb-0">
                        <tbody>
                          <tr>
                            <td>Biaya Tindakan</td>
                            <td className="text-end">{formatRupiah(selectedPemeriksaan.biaya_tindakan)}</td>
                          </tr>
                          {selectedPemeriksaan.obats && selectedPemeriksaan.obats.map((o, i) => (
                            <tr key={i}>
                              <td>{o.nama_obat} x {o.pivot?.jumlah || 0}</td>
                              <td className="text-end">{formatRupiah((o.harga_obat || 0) * (o.pivot?.jumlah || 0))}</td>
                            </tr>
                          ))}
                          <tr className="table-dark">
                            <td><strong>TOTAL</strong></td>
                            <td className="text-end"><strong>{formatRupiah(calculateTotal(selectedPemeriksaan).total)}</strong></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div className="mb-3">
                    <label className="form-label">Metode Pembayaran</label>
                    <select className="form-select" value={form.metode_bayar}
                      onChange={e => setForm({ ...form, metode_bayar: e.target.value })} required>
                      <option value="tunai">Tunai</option>
                      <option value="transfer">Transfer</option>
                      <option value="debit">Debit</option>
                      <option value="qris">QRIS</option>
                    </select>
                  </div>
                </div>
                <div className="modal-footer">
                  <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>Batal</button>
                  <button type="submit" className="btn btn-success">
                    <i className="bi bi-check-circle"></i> Proses Pembayaran
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

export default Pembayaran
