import { NavLink, useNavigate } from 'react-router-dom'

const menuItems = [
  { path: '/', icon: 'bi-speedometer2', label: 'Dashboard' },
  { path: '/users', icon: 'bi-people', label: 'Users' },
  { path: '/pegawai', icon: 'bi-person-badge', label: 'Pegawai' },
  { path: '/dokter-hewan', icon: 'bi-heart-pulse', label: 'Dokter Hewan' },
  { path: '/pemilik-hewan', icon: 'bi-person-badge', label: 'Pemilik Hewan' },
  { path: '/hewan', icon: 'bi-award', label: 'Hewan' },
  { path: '/pendaftaran', icon: 'bi-clipboard-plus', label: 'Pendaftaran' },
  { path: '/pemeriksaan', icon: 'bi-clipboard2-pulse', label: 'Pemeriksaan' },
  { path: '/pembayaran', icon: 'bi-cash-stack', label: 'Pembayaran' },
  { path: '/obat', icon: 'bi-capsule', label: 'Obat' },
]

function Sidebar() {
  const navigate = useNavigate()

  function handleLogout() {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    navigate('/login')
  }

  const user = JSON.parse(localStorage.getItem('user') || '{}')

  return (
    <div className="sidebar d-flex flex-column p-3" style={{ width: '250px' }}>
      <div className="text-white text-center mb-4 pt-2">
        <i className="bi bi-heart-pulse fs-2"></i>
        <h5 className="mt-2">Klinik Hewan</h5>
        <small className="text-muted">{user.name || 'React Frontend'}</small>
      </div>

      <hr className="text-secondary" />

      <nav className="nav flex-column">
        {menuItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            end={item.path === '/'}
            className={({ isActive }) =>
              `nav-link ${isActive ? 'active' : ''}`
            }
          >
            <i className={`bi ${item.icon}`}></i>
            {item.label}
          </NavLink>
        ))}
      </nav>

      <div className="mt-auto text-center">
        <hr className="text-secondary" />
        <button className="btn btn-outline-danger btn-sm w-100" onClick={handleLogout}>
          <i className="bi bi-box-arrow-left me-1"></i>Logout
        </button>
      </div>
    </div>
  )
}

export default Sidebar
