import React, { useState, useEffect } from 'react';
import { RefreshCw, AlertTriangle } from 'lucide-react';

// Import Modular Components
import Sidebar from './Sidebar';
import Header from './Header';
import DashboardTab from './DashboardTab';
import FakultasTab from './FakultasTab';
import ProdiTab from './ProdiTab';
import TahunAkademikTab from './TahunAkademikTab';
import DosenTab from './DosenTab';
import MahasiswaTab from './MahasiswaTab';
import MataKuliahTab from './MataKuliahTab';
import KelasKuliahTab from './KelasKuliahTab';
import LecturerPortalTab from './LecturerPortalTab';
import KelasMahasiswaTab from './KelasMahasiswaTab';
import DynamicFormModal from './DynamicFormModal';
import Login from './Login';

export default function App() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [loading, setLoading] = useState(true);

  // Authentication States
  const [token, setToken] = useState(localStorage.getItem('token') || '');
  const [user, setUser] = useState(null);
  const [checkingAuth, setCheckingAuth] = useState(!!token);

  // Database Data States
  const [fakultas, setFakultas] = useState([]);
  const [prodis, setProdis] = useState([]);
  const [tahunAkademiks, setTahunAkademiks] = useState([]);
  const [dosens, setDosens] = useState([]);
  const [mahasiswas, setMahasiswas] = useState([]);
  const [mataKuliahs, setMataKuliahs] = useState([]);
  const [kelasKuliahs, setKelasKuliahs] = useState([]);
  const [kelasMahasiswas, setKelasMahasiswas] = useState([]);
  const [dosenPengampus, setDosenPengampus] = useState([]);
  const [users, setUsers] = useState([]);

  // Active Semester Dosen View State
  const [selectedDosenForPortal, setSelectedDosenForPortal] = useState('');
  const [dosenActiveClasses, setDosenActiveClasses] = useState([]);
  const [dosenAdviseeStudents, setDosenAdviseeStudents] = useState([]);
  const [loadingPortal, setLoadingPortal] = useState(false);
  const [selectedClassForGrades, setSelectedClassForGrades] = useState(null);
  const [enrolledStudentsInClass, setEnrolledStudentsInClass] = useState([]);
  const [updatingGrades, setUpdatingGrades] = useState({});

  // Modal / Form States
  const [showModal, setShowModal] = useState(false);
  const [modalType, setModalType] = useState(''); // e.g., 'fakultas', 'prodi', etc.
  const [modalAction, setModalAction] = useState('create'); // 'create' | 'edit'
  const [selectedItem, setSelectedItem] = useState(null);
  const [formData, setFormData] = useState({});
  const [formErrors, setFormErrors] = useState({});

  // Custom Delete Modal States
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [deleteTarget, setDeleteTarget] = useState({ type: null, id: null });

  // Active Semester Indicator Helper
  const activeSemester = tahunAkademiks.find(ta => ta.status) || null;

  // Search filter query
  const [searchQuery, setSearchQuery] = useState('');

  // Custom authenticated API fetch wrapper
  const apiFetch = async (url, options = {}) => {
    const currentToken = localStorage.getItem('token');
    const headers = {
      'Accept': 'application/json',
      ...options.headers,
    };
    if (currentToken) {
      headers['Authorization'] = `Bearer ${currentToken}`;
    }
    if (options.body && !(options.body instanceof FormData) && !headers['Content-Type']) {
      headers['Content-Type'] = 'application/json';
    }

    const res = await fetch(url, { ...options, headers });

    if (res.status === 401) {
      handleLogout();
      throw new Error('Session expired. Please log in again.');
    }

    return res;
  };

  // Login handler
  const handleLoginSuccess = (newToken, loggedInUser) => {
    localStorage.setItem('token', newToken);
    setToken(newToken);
    setUser(loggedInUser);

    // Select initial tab based on role
    const roles = loggedInUser.roles || [];
    const isAdmin = roles.some(r => r.name === 'admin');
    const isDosen = roles.some(r => r.name === 'dosen');
    const isMahasiswa = roles.some(r => r.name === 'mahasiswa');

    if (isAdmin) {
      setActiveTab('dashboard');
    } else if (isDosen) {
      setActiveTab('lecturer-portal');
    } else if (isMahasiswa) {
      setActiveTab('kelas-mahasiswa');
    } else {
      setActiveTab('dashboard');
    }
  };

  // Logout handler
  const handleLogout = async () => {
    const currentToken = localStorage.getItem('token');
    try {
      if (currentToken) {
        await fetch('/api/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${currentToken}`
          }
        });
      }
    } catch (err) {
      console.error("Backend logout failed:", err);
    } finally {
      localStorage.removeItem('token');
      setToken('');
      setUser(null);
    }
  };

  // Check current session on mount
  useEffect(() => {
    const checkCurrentUser = async () => {
      if (!token) {
        setCheckingAuth(false);
        return;
      }
      try {
        const res = await fetch('/api/user', {
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        });
        if (res.ok) {
          const data = await res.json();
          setUser(data);
        } else {
          localStorage.removeItem('token');
          setToken('');
          setUser(null);
        }
      } catch (err) {
        console.error("Auth check failed:", err);
      } finally {
        setCheckingAuth(false);
      }
    };
    checkCurrentUser();
  }, [token]);

  // Fetch all data helper
  const fetchData = async () => {
    setLoading(true);
    try {
      const fetchJson = async (url) => {
        const res = await apiFetch(url);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        const data = await res.json();
        return data.data ? data.data : data;
      };

      const [
        fakList, prodList, taList, dosList, mhsList, mkList, kkList, kmList, dpList, userList
      ] = await Promise.all([
        fetchJson('/api/fakultas'),
        fetchJson('/api/prodis'),
        fetchJson('/api/tahun-akademiks'),
        fetchJson('/api/dosens'),
        fetchJson('/api/mahasiswas'),
        fetchJson('/api/mata-kuliahs'),
        fetchJson('/api/kelas-kuliahs'),
        fetchJson('/api/kelas-mahasiswas'),
        fetchJson('/api/dosen-pengampus'),
        fetchJson('/api/users')
      ]);

      setFakultas(fakList);
      setProdis(prodList);
      setTahunAkademiks(taList);
      setDosens(dosList);
      setMahasiswas(mhsList);
      setMataKuliahs(mkList);
      setKelasKuliahs(kkList);
      setKelasMahasiswas(kmList);
      setDosenPengampus(dpList);
      setUsers(userList);
    } catch (error) {
      console.error("Error fetching SIAKAD data:", error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (user) {
      fetchData();
    }
  }, [user]);

  // Auto-select Dosen for lecturer portal if logged in as dosen
  useEffect(() => {
    if (user && dosens.length > 0) {
      const isDosen = (user.roles || []).some(r => r.name === 'dosen');
      if (isDosen) {
        const myDosen = dosens.find(d => d.id_user === user.id);
        if (myDosen && String(selectedDosenForPortal) !== String(myDosen.id)) {
          setSelectedDosenForPortal(String(myDosen.id));
        }
      }
    }
  }, [user, dosens]);


  // Fetch active classes and advisees for a selected Lecturer in the Lecturer Portal
  useEffect(() => {
    if (selectedDosenForPortal) {
      fetchLecturerPortalData(selectedDosenForPortal);
    } else {
      setDosenActiveClasses([]);
      setDosenAdviseeStudents([]);
      setSelectedClassForGrades(null);
      setEnrolledStudentsInClass([]);
    }
  }, [selectedDosenForPortal, kelasKuliahs, tahunAkademiks]);

  const fetchLecturerPortalData = async (dosenId) => {
    setLoadingPortal(true);
    try {
      const [resClasses, resAdvisees] = await Promise.all([
        apiFetch(`/api/dosens/${dosenId}/kelas-kuliah-aktif`),
        apiFetch(`/api/dosens/${dosenId}/mahasiswa-bimbingan`)
      ]);
      
      if (resClasses.ok) {
        const data = await resClasses.json();
        setDosenActiveClasses(data);
      }
      
      if (resAdvisees.ok) {
        const data = await resAdvisees.json();
        setDosenAdviseeStudents(data.data ? data.data : data);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoadingPortal(false);
    }
  };

  // Fetch enrolled students for a specific class in the portal
  const selectClassForPortalGrades = (kelas) => {
    setSelectedClassForGrades(kelas);
    const enrollments = kelasMahasiswas.filter(km => km.id_kelas === kelas.id);
    setEnrolledStudentsInClass(enrollments);

    const gradesMap = {};
    enrollments.forEach(enroll => {
      gradesMap[enroll.id] = {
        nilai_akhir: enroll.nilai_akhir !== null ? enroll.nilai_akhir : '',
        nilai_huruf: enroll.nilai_huruf || ''
      };
    });
    setUpdatingGrades(gradesMap);
  };

  // Save student grade in Lecturer Portal
  const saveStudentGrade = async (enrollId) => {
    const gradeData = updatingGrades[enrollId];
    try {
      const originalEnrollment = kelasMahasiswas.find(km => km.id === enrollId);
      if (!originalEnrollment) return;

      const res = await apiFetch(`/api/kelas-mahasiswas/${enrollId}`, {
        method: 'PUT',
        body: JSON.stringify({
          id_mahasiswa: originalEnrollment.id_mahasiswa,
          id_kelas: originalEnrollment.id_kelas,
          nilai_akhir: gradeData.nilai_akhir === '' ? null : Number(gradeData.nilai_akhir),
          nilai_huruf: gradeData.nilai_huruf || null
        })
      });

      if (res.ok) {
        const updated = await res.json();
        setKelasMahasiswas(prev => prev.map(km => km.id === enrollId ? updated : km));
        alert("Nilai mahasiswa berhasil diperbarui!");
        fetchData();
      } else {
        const errors = await res.json();
        alert("Gagal memperbarui nilai: " + JSON.stringify(errors.errors || errors.message));
      }
    } catch (err) {
      console.error(err);
    }
  };

  // Get correct API endpoint URL based on model type
  const getEndpointUrl = (type, id = null) => {
    let segment = `${type}s`;
    if (type === 'fakultas') {
      segment = 'fakultas';
    } else if (type === 'tahunAkademik') {
      segment = 'tahun-akademiks';
    } else if (type === 'mataKuliah') {
      segment = 'mata-kuliahs';
    } else if (type === 'kelasKuliah') {
      segment = 'kelas-kuliahs';
    } else if (type === 'dosenPengampu') {
      segment = 'dosen-pengampus';
    } else if (type === 'kelasMahasiswa') {
      segment = 'kelas-mahasiswas';
    }
    return `/api/${segment}${id ? `/${id}` : ''}`;
  };

  // Form submit handler
  const handleFormSubmit = async (e) => {
    e.preventDefault();
    setFormErrors({});

    const url = getEndpointUrl(modalType, modalAction === 'edit' ? selectedItem.id : null);
    const method = modalAction === 'edit' ? 'PUT' : 'POST';

    try {
      const res = await apiFetch(url, {
        method,
        body: JSON.stringify(formData)
      });

      const responseData = await res.json();

      if (res.ok) {
        setShowModal(false);
        setFormData({});
        fetchData();
      } else {
        if (responseData.errors) {
          setFormErrors(responseData.errors);
        } else {
          alert("Error: " + responseData.message);
        }
      }
    } catch (err) {
      console.error(err);
      alert("Terjadi kesalahan koneksi server.");
    }
  };

  // Delete handler (triggers custom modal)
  const handleDeleteItem = (type, id) => {
    console.log("handleDeleteItem triggered: type =", type, ", id =", id);
    setDeleteTarget({ type, id });
    setShowDeleteModal(true);
  };

  // Actual API delete logic executed on custom modal confirmation
  const confirmDelete = async () => {
    const { type, id } = deleteTarget;
    console.log("confirmDelete executed for type =", type, ", id =", id);
    setShowDeleteModal(false);
    try {
      const res = await apiFetch(getEndpointUrl(type, id), {
        method: 'DELETE',
      });

      if (res.ok) {
        fetchData();
      } else {
        let errMsg = "Constraint Error";
        try {
          const err = await res.json();
          errMsg = err.message || errMsg;
        } catch (e) {
          errMsg = `Server error (${res.status})`;
        }
        alert("Gagal menghapus data: " + errMsg);
      }
    } catch (err) {
      console.error(err);
      alert("Terjadi kesalahan koneksi server.");
    } finally {
      setDeleteTarget({ type: null, id: null });
    }
  };

  // Open modal helper
  const openModal = (type, action, item = null) => {
    setModalType(type);
    setModalAction(action);
    setSelectedItem(item);
    setFormErrors({});

    if (action === 'edit' && item) {
      setFormData({ ...item });
    } else if (type === 'dosenPengampu' && item) {
      setFormData({ id_kelas: item.id }); // Use correct property 'id' from kelasKuliah item
    } else {
      setFormData({});
    }

    setShowModal(true);
  };

  // Switch academic year active status
  const toggleTahunAkademikStatus = async (ta) => {
    try {
      const res = await apiFetch(`/api/tahun-akademiks/${ta.id}`, {
        method: 'PUT',
        body: JSON.stringify({
          kode_ta: ta.kode_ta,
          nama_ta: ta.nama_ta,
          status: true
        })
      });

      if (res.ok) {
        fetchData();
      } else {
        const err = await res.json();
        alert("Gagal mengaktifkan tahun akademik: " + err.message);
      }
    } catch (err) {
      console.error(err);
    }
  };

  if (checkingAuth) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen bg-monday-background gap-4">
        <RefreshCw size={40} className="text-monday-blue animate-spin" />
        <p className="text-monday-gray text-sm font-bold">Menghubungkan ke server...</p>
      </div>
    );
  }

  if (!user) {
    return <Login onLoginSuccess={handleLoginSuccess} />;
  }

  return (
    <div className="flex min-h-screen bg-monday-background text-monday-black font-sans">

      {/* Sidebar Navigation */}
      <Sidebar
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        setSearchQuery={setSearchQuery}
        user={user}
        onLogout={handleLogout}
      />

      {/* Main Container */}
      <main className="flex-1 flex flex-col min-w-0 overflow-y-auto">

        {/* Top Header Bar */}
        <Header
          activeTab={activeTab}
          activeSemester={activeSemester}
          loading={loading}
          fetchData={fetchData}
          user={user}
          onLogout={handleLogout}
        />

        {/* Content Body */}
        <div className="px-8 pb-12 w-full space-y-6 flex-1">
          {loading ? (
            <div className="flex flex-col items-center justify-center py-40 gap-4">
              <RefreshCw size={40} className="text-monday-blue animate-spin" />
              <p className="text-monday-gray text-sm font-semibold">Memuat data dari server...</p>
            </div>
          ) : (
            <>
              {/* DASHBOARD TAB */}
              {activeTab === 'dashboard' && (
                <DashboardTab
                  fakultas={fakultas}
                  prodis={prodis}
                  dosens={dosens}
                  mahasiswas={mahasiswas}
                  activeSemester={activeSemester}
                  kelasKuliahs={kelasKuliahs}
                  dosenPengampus={dosenPengampus}
                  setActiveTab={setActiveTab}
                />
              )}

              {/* FAKULTAS TAB */}
              {activeTab === 'fakultas' && (
                <FakultasTab
                  fakultas={fakultas}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* PROGRAM STUDI TAB */}
              {activeTab === 'prodi' && (
                <ProdiTab
                  prodis={prodis}
                  fakultas={fakultas}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* TAHUN AKADEMIK TAB */}
              {activeTab === 'tahun-akademik' && (
                <TahunAkademikTab
                  tahunAkademiks={tahunAkademiks}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                  toggleTahunAkademikStatus={toggleTahunAkademikStatus}
                />
              )}

              {/* DATA DOSEN TAB */}
              {activeTab === 'dosen' && (
                <DosenTab
                  dosens={dosens}
                  users={users}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* DATA MAHASISWA TAB */}
              {activeTab === 'mahasiswa' && (
                <MahasiswaTab
                  mahasiswas={mahasiswas}
                  prodis={prodis}
                  dosens={dosens}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* MATA KULIAH TAB */}
              {activeTab === 'mata-kuliah' && (
                <MataKuliahTab
                  mataKuliahs={mataKuliahs}
                  prodis={prodis}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* KELAS KULIAH TAB */}
              {activeTab === 'kelas-kuliah' && (
                <KelasKuliahTab
                  kelasKuliahs={kelasKuliahs}
                  mataKuliahs={mataKuliahs}
                  tahunAkademiks={tahunAkademiks}
                  dosenPengampus={dosenPengampus}
                  dosens={dosens}
                  kelasMahasiswas={kelasMahasiswas}
                  mahasiswas={mahasiswas}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* PORTAL DOSEN TAB */}
              {activeTab === 'lecturer-portal' && (
                <LecturerPortalTab
                  user={user}
                  dosens={dosens}
                  mataKuliahs={mataKuliahs}
                  mahasiswas={mahasiswas}
                  kelasKuliahs={kelasKuliahs}
                  kelasMahasiswas={kelasMahasiswas}
                  dosenActiveClasses={dosenActiveClasses}
                  dosenAdviseeStudents={dosenAdviseeStudents}
                  prodis={prodis}
                  loadingPortal={loadingPortal}
                  selectedDosenForPortal={selectedDosenForPortal}
                  setSelectedDosenForPortal={setSelectedDosenForPortal}
                  selectedClassForGrades={selectedClassForGrades}
                  setSelectedClassForGrades={setSelectedClassForGrades}
                  enrolledStudentsInClass={enrolledStudentsInClass}
                  setEnrolledStudentsInClass={setEnrolledStudentsInClass}
                  updatingGrades={updatingGrades}
                  setUpdatingGrades={setUpdatingGrades}
                  fetchLecturerPortalData={fetchLecturerPortalData}
                  selectClassForPortalGrades={selectClassForPortalGrades}
                  saveStudentGrade={saveStudentGrade}
                />
              )}

              {/* KELAS MAHASISWA TAB (KRS/KHS) */}
              {activeTab === 'kelas-mahasiswa' && (
                <KelasMahasiswaTab
                  kelasMahasiswas={kelasMahasiswas}
                  mahasiswas={mahasiswas}
                  kelasKuliahs={kelasKuliahs}
                  mataKuliahs={mataKuliahs}
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}
            </>
          )}
        </div>
      </main>

      {/* Dynamic Form Overlay Modal */}
      <DynamicFormModal
        showModal={showModal}
        setShowModal={setShowModal}
        modalType={modalType}
        modalAction={modalAction}
        selectedItem={selectedItem}
        formData={formData}
        setFormData={setFormData}
        formErrors={formErrors}
        fakultas={fakultas}
        prodis={prodis}
        tahunAkademiks={tahunAkademiks}
        dosens={dosens}
        mahasiswas={mahasiswas}
        mataKuliahs={mataKuliahs}
        kelasKuliahs={kelasKuliahs}
        users={users}
        handleFormSubmit={handleFormSubmit}
      />

      {/* Custom Delete Confirmation Modal */}
      {showDeleteModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-monday-black/40 backdrop-blur-sm p-4 animate-fade-in">
          <div className="bg-white rounded-3xl p-6 shadow-2xl border border-monday-border max-w-md w-full flex flex-col gap-5 transform scale-100 transition-all duration-300">
            <div className="flex items-start gap-4">
              <div className="p-3 bg-monday-red/10 text-monday-red rounded-2xl shrink-0">
                <AlertTriangle size={28} />
              </div>
              <div className="flex flex-col gap-1.5">
                <h3 className="font-extrabold text-lg text-monday-black">
                  Konfirmasi Hapus Data
                </h3>
                <p className="text-sm font-semibold text-monday-gray leading-relaxed">
                  Apakah Anda yakin ingin menghapus data ini secara permanen? Tindakan ini tidak dapat dibatalkan.
                </p>
              </div>
            </div>

            <div className="flex items-center justify-end gap-3 pt-2 border-t border-monday-border">
              <button
                type="button"
                onClick={() => {
                  setShowDeleteModal(false);
                  setDeleteTarget({ type: null, id: null });
                }}
                className="px-5 py-2.5 bg-monday-background border border-monday-border text-monday-black hover:bg-monday-gray-background rounded-full font-bold text-sm transition-all duration-200"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={confirmDelete}
                className="px-5 py-2.5 bg-monday-red text-white hover:bg-opacity-90 rounded-full font-bold text-sm shadow-md shadow-monday-red/20 transition-all duration-200"
              >
                Ya, Hapus
              </button>
            </div>
          </div>
        </div>
      )}

    </div>
  );
}


