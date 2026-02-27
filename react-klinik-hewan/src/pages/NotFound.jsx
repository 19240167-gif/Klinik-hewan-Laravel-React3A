import { Link } from 'react-router-dom'

function NotFound() {
  return (
    <div className="p-4 text-center" style={{ marginTop: '10vh' }}>
      <h1 className="display-1 text-muted">404</h1>
      <h3 className="mb-3">Halaman Tidak Ditemukan</h3>
      <p className="text-muted mb-4">
        Halaman yang Anda cari tidak tersedia atau telah dipindahkan.
      </p>
      <Link to="/" className="btn btn-primary">
        <i className="bi bi-house me-1"></i>Kembali ke Dashboard
      </Link>
    </div>
  )
}

export default NotFound
