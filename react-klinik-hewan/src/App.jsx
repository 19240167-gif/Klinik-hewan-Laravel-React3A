import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom'
import Layout from './components/Layout'
import Dashboard from './pages/Dashboard'
import Users from './pages/Users'
import PemilikHewan from './pages/PemilikHewan'
import Hewan from './pages/Hewan'
import Obat from './pages/Obat'
import Pegawai from './pages/Pegawai'
import DokterHewan from './pages/DokterHewan'
import Pendaftaran from './pages/Pendaftaran'
import Pemeriksaan from './pages/Pemeriksaan'
import Pembayaran from './pages/Pembayaran'
import Login from './pages/Login'
import NotFound from './pages/NotFound'

function PrivateRoute({ children }) {
  const token = localStorage.getItem('token')
  return token ? children : <Navigate to="/login" />
}

/**
 * RoleRoute - hanya mengizinkan role tertentu mengakses halaman
 * Jika role tidak cocok, redirect ke halaman default sesuai role
 */
function RoleRoute({ children, allowedRoles }) {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  const role = user.role || ''

  if (!allowedRoles.includes(role)) {
    // Redirect ke halaman default sesuai role
    switch (role) {
      case 'admin': return <Navigate to="/dashboard" replace />
      case 'pegawai': return <Navigate to="/pemilik-hewan" replace />
      case 'dokter': return <Navigate to="/pemeriksaan" replace />
      default: return <Navigate to="/login" replace />
    }
  }

  return children
}

/**
 * IndexRedirect - redirect halaman index "/" berdasarkan role user
 */
function IndexRedirect() {
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  const role = user.role || ''

  switch (role) {
    case 'admin': return <Navigate to="/dashboard" replace />
    case 'pegawai': return <Navigate to="/pemilik-hewan" replace />
    case 'dokter': return <Navigate to="/pemeriksaan" replace />
    default: return <Navigate to="/login" replace />
  }
}

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/" element={<PrivateRoute><Layout /></PrivateRoute>}>
          {/* Index redirect berdasarkan role */}
          <Route index element={<IndexRedirect />} />

          {/* Admin only */}
          <Route path="dashboard" element={<RoleRoute allowedRoles={['admin']}><Dashboard /></RoleRoute>} />
          <Route path="users" element={<RoleRoute allowedRoles={['admin']}><Users /></RoleRoute>} />
          <Route path="pegawai" element={<RoleRoute allowedRoles={['admin']}><Pegawai /></RoleRoute>} />
          <Route path="dokter-hewan" element={<RoleRoute allowedRoles={['admin']}><DokterHewan /></RoleRoute>} />

          {/* Admin & Pegawai */}
          <Route path="pemilik-hewan" element={<RoleRoute allowedRoles={['admin', 'pegawai']}><PemilikHewan /></RoleRoute>} />
          <Route path="hewan" element={<RoleRoute allowedRoles={['admin', 'pegawai']}><Hewan /></RoleRoute>} />
          <Route path="pendaftaran" element={<RoleRoute allowedRoles={['admin', 'pegawai']}><Pendaftaran /></RoleRoute>} />
          <Route path="pembayaran" element={<RoleRoute allowedRoles={['admin', 'pegawai']}><Pembayaran /></RoleRoute>} />

          {/* Admin & Dokter */}
          <Route path="pemeriksaan" element={<RoleRoute allowedRoles={['admin', 'dokter']}><Pemeriksaan /></RoleRoute>} />

          {/* Admin, Pegawai & Dokter */}
          <Route path="obat" element={<RoleRoute allowedRoles={['admin', 'pegawai', 'dokter']}><Obat /></RoleRoute>} />

          <Route path="*" element={<NotFound />} />
        </Route>
      </Routes>
    </Router>
  )
}

export default App
