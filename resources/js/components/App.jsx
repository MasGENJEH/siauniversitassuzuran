import React, { useState, useEffect } from 'react';
import { RefreshCw } from 'lucide-react';

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

export default function App() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [loading, setLoading] = useState(true);

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

  // Active Semester Indicator Helper
  const activeSemester = tahunAkademiks.find(ta => ta.status) || null;

  // Search filter query
  const [searchQuery, setSearchQuery] = useState('');

  // Fetch all data helper
  const fetchData = async () => {
    setLoading(true);
    try {
      const fetchJson = async (url) => {
        const res = await fetch(url);
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
    fetchData();
  }, []);

  // Fetch active classes for a selected Lecturer in the Lecturer Portal
  useEffect(() => {
    if (selectedDosenForPortal) {
      fetchLecturerPortalData(selectedDosenForPortal);
    } else {
      setDosenActiveClasses([]);
      setSelectedClassForGrades(null);
      setEnrolledStudentsInClass([]);
    }
  }, [selectedDosenForPortal, kelasKuliahs, tahunAkademiks]);

  const fetchLecturerPortalData = async (dosenId) => {
    setLoadingPortal(true);
    try {
      const res = await fetch(`/api/dosens/${dosenId}/kelas-kuliah-aktif`);
      if (res.ok) {
        const data = await res.json();
        setDosenActiveClasses(data);
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

      const res = await fetch(`/api/kelas-mahasiswas/${enrollId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
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

  // Form submit handler
  const handleFormSubmit = async (e) => {
    e.preventDefault();
    setFormErrors({});
    let url = `/api/${modalType}s`;
    let method = 'POST';

    // Pluralize mapping corrections for API endpoints
    if (modalType === 'tahunAkademik') {
      url = `/api/tahun-akademiks`;
    } else if (modalType === 'mataKuliah') {
      url = `/api/mata-kuliahs`;
    } else if (modalType === 'kelasKuliah') {
      url = `/api/kelas-kuliahs`;
    } else if (modalType === 'dosenPengampu') {
      url = `/api/dosen-pengampus`;
    } else if (modalType === 'kelasMahasiswa') {
      url = `/api/kelas-mahasiswas`;
    }

    if (modalAction === 'edit') {
      if (modalType === 'tahunAkademik') {
        url = `/api/tahun-akademiks/${selectedItem.id}`;
      } else if (modalType === 'mataKuliah') {
        url = `/api/mata-kuliahs/${selectedItem.id}`;
      } else if (modalType === 'kelasKuliah') {
        url = `/api/kelas-kuliahs/${selectedItem.id}`;
      } else if (modalType === 'dosenPengampu') {
        url = `/api/dosen-pengampus/${selectedItem.id}`;
      } else if (modalType === 'kelasMahasiswa') {
        url = `/api/kelas-mahasiswas/${selectedItem.id}`;
      } else {
        url = `/api/${modalType}s/${selectedItem.id}`;
      }
      method = 'PUT';
    }

    try {
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
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

  // Delete handler
  const handleDeleteItem = async (type, id) => {
    if (!confirm("Apakah Anda yakin ingin menghapus data ini secara permanen?")) return;

    try {
      const res = await fetch(`/api/${type}s/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
      });

      if (res.ok) {
        fetchData();
      } else {
        const err = await res.json();
        alert("Gagal menghapus data: " + (err.message || "Constraint Error"));
      }
    } catch (err) {
      console.error(err);
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
      const res = await fetch(`/api/tahun-akademiks/${ta.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
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

  return (
    <div className="flex min-h-screen bg-monday-background text-monday-black font-sans">
      
      {/* Sidebar Navigation */}
      <Sidebar 
        activeTab={activeTab} 
        setActiveTab={setActiveTab} 
        setSearchQuery={setSearchQuery} 
      />

      {/* Main Container */}
      <main className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        {/* Top Header Bar */}
        <Header 
          activeTab={activeTab} 
          activeSemester={activeSemester} 
          loading={loading} 
          fetchData={fetchData} 
        />

        {/* Content Body */}
        <div className="px-8 pb-12 max-w-7xl w-full mx-auto space-y-6 flex-1">
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
                  searchQuery={searchQuery}
                  setSearchQuery={setSearchQuery}
                  openModal={openModal}
                  handleDeleteItem={handleDeleteItem}
                />
              )}

              {/* PORTAL DOSEN TAB */}
              {activeTab === 'lecturer-portal' && (
                <LecturerPortalTab 
                  dosens={dosens}
                  mataKuliahs={mataKuliahs}
                  mahasiswas={mahasiswas}
                  kelasKuliahs={kelasKuliahs}
                  kelasMahasiswas={kelasMahasiswas}
                  dosenActiveClasses={dosenActiveClasses}
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

    </div>
  );
}
