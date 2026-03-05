import { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import Loading from '../components/Loading'
import api from '../services/api'

function Dashboard() {
  const [stats, setStats] = useState({ pemilik: 0, hewan: 0, obat: 0, users: 0, pegawai: 0, dokter: 0, pendaftaran: 0, pemeriksaan: 0, pembayaran: 0 })
  const [loading, setLoading] = useState(true)
  const user = JSON.parse(localStorage.getItem('user') || '{}')

  useEffect(() => {
    async function fetchStats() {
      try {
        const [pemilikRes, hewanRes, obatRes, usersRes, pegawaiRes, dokterRes, pendaftaranRes, pemeriksaanRes, pembayaranRes] = await Promise.allSettled([
          api.get('/pemilik-hewan'),
          api.get('/hewan'),
          api.get('/obat'),
          api.get('/users'),
          api.get('/pegawai'),
          api.get('/dokter-hewan'),
          api.get('/pendaftaran'),
          api.get('/pemeriksaan'),
          api.get('/pembayaran'),
        ])
        setStats({
          pemilik: pemilikRes.status === 'fulfilled' ? (pemilikRes.value.data.data?.length ?? 0) : 0,
          hewan: hewanRes.status === 'fulfilled' ? (hewanRes.value.data.data?.length ?? 0) : 0,
          obat: obatRes.status === 'fulfilled' ? (obatRes.value.data.data?.length ?? 0) : 0,
          users: usersRes.status === 'fulfilled' ? (usersRes.value.data.data?.length ?? 0) : 0,
          pegawai: pegawaiRes.status === 'fulfilled' ? (pegawaiRes.value.data.data?.length ?? 0) : 0,
          dokter: dokterRes.status === 'fulfilled' ? (dokterRes.value.data.data?.length ?? 0) : 0,
          pendaftaran: pendaftaranRes.status === 'fulfilled' ? (pendaftaranRes.value.data.data?.length ?? 0) : 0,
          pemeriksaan: pemeriksaanRes.status === 'fulfilled' ? (pemeriksaanRes.value.data.data?.length ?? 0) : 0,
          pembayaran: pembayaranRes.status === 'fulfilled' ? (pembayaranRes.value.data.data?.length ?? 0) : 0,
        })
      } catch (err) {
        console.error('Error fetching stats:', err)
      } finally {
        setLoading(false)
      }
    }
    fetchStats()
  }, [])

  if (loading) return <Loading />

  const cards = [
    { label: 'Pemilik Hewan', value: stats.pemilik, icon: 'bi-people', color: 'primary', link: '/pemilik-hewan' },
    { label: 'Hewan', value: stats.hewan, icon: 'bi-award', color: 'success', link: '/hewan' },
    { label: 'Pegawai', value: stats.pegawai, icon: 'bi-person-badge', color: 'secondary', link: '/pegawai' },
    { label: 'Dokter Hewan', value: stats.dokter, icon: 'bi-heart-pulse', color: 'danger', link: '/dokter-hewan' },
    { label: 'Pendaftaran', value: stats.pendaftaran, icon: 'bi-clipboard-plus', color: 'dark', link: '/pendaftaran' },
    { label: 'Pemeriksaan', value: stats.pemeriksaan, icon: 'bi-clipboard2-pulse', color: 'info', link: '/pemeriksaan' },
    { label: 'Pembayaran', value: stats.pembayaran, icon: 'bi-cash-stack', color: 'success', link: '/pembayaran' },
    { label: 'Obat', value: stats.obat, icon: 'bi-capsule', color: 'warning', link: '/obat' },
    { label: 'Users', value: stats.users, icon: 'bi-people', color: 'info', link: '/users' },
  ]

  return (
    <>
      <div className="row mb-4">
        <div className="col">
          <h2><i className="bi bi-speedometer2"></i> Dashboard Admin</h2>
          <p className="text-muted">Selamat datang, {user.name || 'Admin'}</p>
        </div>
      </div>

      <div className="row g-3">
        {cards.map((card) => (
          <div className="col-md-3" key={card.label}>
            <div className={`card text-white bg-${card.color}`}>
              <div className="card-body">
                <div className="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 className="card-title">{card.label}</h6>
                    <h2 className="mb-0">{card.value}</h2>
                  </div>
                  <i className={`bi ${card.icon} fs-1 opacity-50`}></i>
                </div>
              </div>
              <div className={`card-footer bg-${card.color} bg-opacity-75`}>
                <Link to={card.link} className="text-white text-decoration-none small">
                  Lihat Detail <i className="bi bi-arrow-right"></i>
                </Link>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="row mt-4">
        <div className="col-md-8">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0"><i className="bi bi-info-circle"></i> Informasi</h5>
            </div>
            <div className="card-body">
              <p className="text-muted mb-0">
                Sistem informasi manajemen klinik hewan. Gunakan menu navigasi di atas untuk mengelola data.
              </p>
            </div>
          </div>
        </div>

        <div className="col-md-4">
          <div className="card">
            <div className="card-header">
              <h5 className="mb-0"><i className="bi bi-lightning"></i> Quick Actions</h5>
            </div>
            <div className="card-body">
              <div className="d-grid gap-2">
                <Link to="/pendaftaran" className="btn btn-outline-dark">
                  <i className="bi bi-plus-circle"></i> Tambah Pendaftaran
                </Link>
                <Link to="/pemilik-hewan" className="btn btn-outline-primary">
                  <i className="bi bi-plus-circle"></i> Tambah Pemilik Hewan
                </Link>
                <Link to="/hewan" className="btn btn-outline-success">
                  <i className="bi bi-plus-circle"></i> Tambah Hewan
                </Link>
                <Link to="/obat" className="btn btn-outline-warning">
                  <i className="bi bi-plus-circle"></i> Tambah Obat
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

export default Dashboard
