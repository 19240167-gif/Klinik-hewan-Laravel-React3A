import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useState, useRef, useEffect } from 'react'

function Navbar() {
  const navigate = useNavigate()
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  const role = user.role || ''

  // State for dropdowns
  const [openDropdown, setOpenDropdown] = useState(null)
  const [collapsed, setCollapsed] = useState(true)
  const navRef = useRef(null)

  // Close dropdowns when clicking outside
  useEffect(() => {
    function handleClickOutside(e) {
      if (navRef.current && !navRef.current.contains(e.target)) {
        setOpenDropdown(null)
      }
    }
    document.addEventListener('mousedown', handleClickOutside)
    return () => document.removeEventListener('mousedown', handleClickOutside)
  }, [])

  function toggleDropdown(name) {
    setOpenDropdown(prev => prev === name ? null : name)
  }

  function handleLogout() {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    navigate('/login')
  }

  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-primary" ref={navRef}>
      <div className="container">
        <Link className="navbar-brand" to="/">
          <i className="bi bi-heart-pulse"></i> Klinik Hewan
        </Link>
        <button
          className="navbar-toggler"
          type="button"
          onClick={() => setCollapsed(!collapsed)}
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        <div className={`collapse navbar-collapse ${collapsed ? '' : 'show'}`}>
          <ul className="navbar-nav ms-auto">

            {/* Dashboard - Admin only */}
            {role === 'admin' && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/dashboard">
                  <i className="bi bi-speedometer2"></i> Dashboard
                </NavLink>
              </li>
            )}

            {/* Manajemen dropdown - Admin only */}
            {role === 'admin' && (
              <li className={`nav-item dropdown ${openDropdown === 'manajemen' ? 'show' : ''}`}>
                <a
                  className="nav-link dropdown-toggle"
                  href="#"
                  onClick={(e) => { e.preventDefault(); toggleDropdown('manajemen') }}
                >
                  <i className="bi bi-gear"></i> Manajemen
                </a>
                <ul className={`dropdown-menu ${openDropdown === 'manajemen' ? 'show' : ''}`}>
                  <li>
                    <Link className="dropdown-item" to="/users" onClick={() => setOpenDropdown(null)}>
                      <i className="bi bi-people"></i> Users
                    </Link>
                  </li>
                  <li>
                    <Link className="dropdown-item" to="/pegawai" onClick={() => setOpenDropdown(null)}>
                      <i className="bi bi-person-badge"></i> Pegawai
                    </Link>
                  </li>
                  <li>
                    <Link className="dropdown-item" to="/dokter-hewan" onClick={() => setOpenDropdown(null)}>
                      <i className="bi bi-heart-pulse"></i> Dokter Hewan
                    </Link>
                  </li>
                </ul>
              </li>
            )}

            {/* Pemilik Hewan - Admin, Pegawai */}
            {['admin', 'pegawai'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/pemilik-hewan">
                  <i className="bi bi-people"></i> Pemilik Hewan
                </NavLink>
              </li>
            )}

            {/* Hewan - Admin, Pegawai */}
            {['admin', 'pegawai'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/hewan">
                  <i className="bi bi-award"></i> Hewan
                </NavLink>
              </li>
            )}

            {/* Pendaftaran - Admin, Pegawai */}
            {['admin', 'pegawai'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/pendaftaran">
                  <i className="bi bi-clipboard-plus"></i> Pendaftaran
                </NavLink>
              </li>
            )}

            {/* Pemeriksaan - Admin, Dokter */}
            {['admin', 'dokter'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/pemeriksaan">
                  <i className="bi bi-clipboard2-pulse"></i> Pemeriksaan
                </NavLink>
              </li>
            )}

            {/* Pembayaran - Admin, Pegawai */}
            {['admin', 'pegawai'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/pembayaran">
                  <i className="bi bi-cash-stack"></i> Pembayaran
                </NavLink>
              </li>
            )}

            {/* Obat - Admin, Pegawai, Dokter */}
            {['admin', 'pegawai', 'dokter'].includes(role) && (
              <li className="nav-item">
                <NavLink className="nav-link" to="/obat">
                  <i className="bi bi-capsule"></i> Obat
                </NavLink>
              </li>
            )}

            {/* User dropdown */}
            <li className={`nav-item dropdown ${openDropdown === 'user' ? 'show' : ''}`}>
              <a
                className="nav-link dropdown-toggle"
                href="#"
                onClick={(e) => { e.preventDefault(); toggleDropdown('user') }}
              >
                <i className="bi bi-person-circle"></i> {user.name || 'User'}
              </a>
              <ul className={`dropdown-menu dropdown-menu-end ${openDropdown === 'user' ? 'show' : ''}`}>
                <li>
                  <span className="dropdown-item-text small">
                    <strong>Role:</strong> {role ? role.charAt(0).toUpperCase() + role.slice(1) : '-'}
                  </span>
                </li>
                <li><hr className="dropdown-divider" /></li>
                <li>
                  <button className="dropdown-item text-danger" onClick={handleLogout}>
                    <i className="bi bi-box-arrow-right"></i> Logout
                  </button>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      </div>
    </nav>
  )
}

export default Navbar
