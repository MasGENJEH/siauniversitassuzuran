import React, { useState, useEffect, useMemo } from 'react';
import { GraduationCap, Plus, Search, Edit, Trash2, ChevronLeft, ChevronRight, Eye, EyeOff, ArrowLeft, User, BookOpen, Award, Calendar, Clock, ShieldCheck, MapPin, Users, TrendingUp, Mail, Lock } from 'lucide-react';

export default function MahasiswaTab({
  students,
  studyPrograms,
  lecturers,
  faculties,
  kelasMahasiswas,
  kelasKuliahs,
  mataKuliahs,
  tahunAkademiks,
  users = [],
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedMahasiswa, setSelectedMahasiswa] = useState(null);
  const [showPassword, setShowPassword] = useState(false);
  const itemsPerPage = 10;

  // Reset to page 1 when search query changes
  useEffect(() => {
    setCurrentPage(1);
  }, [searchQuery]);

  // Reset showPassword when selectedMahasiswa changes
  useEffect(() => {
    setShowPassword(false);
  }, [selectedMahasiswa]);

  // Filter items based on search query
  const filteredItems = students.filter(m =>
    m.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    m.nim.toLowerCase().includes(searchQuery.toLowerCase())
  );

  // Pagination bounds & slice
  const totalItems = filteredItems.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
  const paginatedItems = filteredItems.slice(startIndex, endIndex);

  // Helper for pagination window
  const getPageNumbers = () => {
    if (totalPages <= 5) {
      return Array.from({ length: totalPages }, (_, i) => i + 1);
    }
    if (currentPage <= 3) {
      return [1, 2, 3, 4, 5, '...', totalPages];
    }
    if (currentPage >= totalPages - 2) {
      return [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
    }
    return [1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages];
  };

  // Compute detail data for selected mahasiswa
  const detailData = useMemo(() => {
    if (!selectedMahasiswa) return null;

    const mhs = selectedMahasiswa;
    const prodiObj = studyPrograms.find(p => p.id === mhs.study_program_id);
    const dosenObj = lecturers.find(d => d.id === mhs.academic_advisor_id);
    const fakObj = prodiObj ? (faculties || []).find(f => f.id === prodiObj.faculty_id) : null;
    const userObj = users.find(u => u.id === mhs.user_id);

    // Get all enrollments for this student
    const enrollments = kelasMahasiswas.filter(km => km.student_id === mhs.id);

    // Calculate total SKS & unique semesters
    let totalSks = 0;
    let totalNilai = 0;
    let gradedCount = 0;
    const semesterSet = new Set();
    const courseDetails = [];

    enrollments.forEach(km => {
      const kk = kelasKuliahs.find(k => k.id === km.course_class_id);
      const mk = kk ? mataKuliahs.find(m => m.id === kk.course_id) : null;
      const ta = kk ? tahunAkademiks.find(t => t.id === kk.academic_year_id) : null;

      if (mk && mk.sks) totalSks += Number(mk.sks);
      if (ta) semesterSet.add(ta.id);
      if (km.final_score !== null && km.final_score !== undefined) {
        totalNilai += Number(km.final_score);
        gradedCount++;
      }

      courseDetails.push({ km, kk, mk, ta });
    });

    const avgNilai = gradedCount > 0 ? (totalNilai / gradedCount).toFixed(1) : null;

    // Compute IPK-style (weighted GPA) - simplified: bobot * sks
    let totalBobot = 0;
    let totalSksBerbobot = 0;
    enrollments.forEach(km => {
      const kk = kelasKuliahs.find(k => k.id === km.course_class_id);
      const mk = kk ? mataKuliahs.find(m => m.id === kk.course_id) : null;
      if (mk && mk.sks && km.letter_grade) {
        const bobotMap = { 'A': 4, 'B': 3, 'C': 2, 'D': 1, 'E': 0 };
        const bobot = bobotMap[km.letter_grade] ?? 0;
        totalBobot += bobot * Number(mk.sks);
        totalSksBerbobot += Number(mk.sks);
      }
    });
    const ipk = totalSksBerbobot > 0 ? (totalBobot / totalSksBerbobot).toFixed(2) : null;

    return {
      mahasiswa: mhs,
      prodi: prodiObj,
      dosen: dosenObj,
      faculties: fakObj,
      user: userObj,
      totalSks,
      totalMk: enrollments.length,
      semesterCount: semesterSet.size,
      avgNilai,
      ipk,
      courseDetails,
    };
  }, [selectedMahasiswa, studyPrograms, lecturers, faculties, kelasMahasiswas, kelasKuliahs, mataKuliahs, tahunAkademiks, users]);

  // Status badge color helper
  const getStatusBadge = (status) => {
    switch (status) {
      case 'AKTIF': return 'bg-emerald-500/15 text-emerald-700 border-emerald-500/20';
      case 'CUTI': return 'bg-amber-500/15 text-amber-700 border-amber-500/20';
      case 'LULUS': return 'bg-monday-blue/15 text-monday-blue border-monday-blue/20';
      case 'DO': return 'bg-monday-red/15 text-monday-red border-monday-red/20';
      default: return 'bg-monday-background text-monday-gray border-monday-border';
    }
  };

  // Grade letter badge
  const getGradeBadgeClass = (grade) => {
    switch (grade) {
      case 'A': return 'bg-emerald-500/15 text-emerald-700 border-emerald-500/20';
      case 'B': return 'bg-monday-blue/15 text-monday-blue border-monday-blue/20';
      case 'C': return 'bg-amber-500/15 text-amber-700 border-amber-500/20';
      case 'D': return 'bg-monday-gray/15 text-monday-gray border-monday-gray/20';
      case 'E': return 'bg-monday-red/15 text-monday-red border-monday-red/20';
      default: return 'bg-monday-background text-monday-gray border-monday-border';
    }
  };

  // Photo component with fallback
  const StudentPhoto = ({ mahasiswa, size = 'lg' }) => {
    const [imgError, setImgError] = useState(false);
    const sizeClasses = size === 'lg' ? 'w-28 h-28' : size === 'md' ? 'w-16 h-16' : 'w-10 h-10';
    const iconSize = size === 'lg' ? 48 : size === 'md' ? 24 : 16;
    const textSize = size === 'lg' ? 'text-4xl' : size === 'md' ? 'text-xl' : 'text-sm';

    const photoUrl = mahasiswa.photo ? `/storage/${mahasiswa.photo}` : null;

    if (photoUrl && !imgError) {
      return (
        <div className={`${sizeClasses} rounded-2xl overflow-hidden border-2 border-monday-blue/20 shadow-lg shadow-monday-blue/10 flex-shrink-0 ${size === 'lg' ? 'border-4 border-white bg-white relative z-20 shadow-xl' : ''}`}>
          <img
            src={photoUrl}
            alt={mahasiswa.name}
            className="w-full h-full object-cover"
            onError={() => setImgError(true)}
          />
        </div>
      );
    }

    // Fallback: user icon with solid bg for lg size to block banner background
    return (
      <div className={`${sizeClasses} rounded-2xl ${size === 'lg' ? 'bg-white border-4 border-white relative z-20 shadow-xl' : 'bg-gradient-to-br from-monday-blue/20 via-monday-blue/10 to-indigo-500/10 border-2 border-monday-blue/20 shadow-lg shadow-monday-blue/10'} flex items-center justify-center flex-shrink-0`}>
        {size === 'lg' ? (
          <div className="w-full h-full rounded-xl bg-gradient-to-br from-monday-blue/10 to-indigo-500/5 flex items-center justify-center">
            <User size={iconSize} className="text-monday-blue/60" strokeWidth={1.5} />
          </div>
        ) : (
          <User size={iconSize} className="text-monday-blue/60" strokeWidth={1.5} />
        )}
      </div>
    );
  };

  // ─── DETAIL VIEW ───────────────────────────────────────────────────
  if (selectedMahasiswa && detailData) {
    return (
      <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
        {/* Back Button & Header */}
        <div className="flex items-center justify-between pb-4 border-b border-monday-border">
          <div className="flex items-center gap-4">
            <button
              onClick={() => setSelectedMahasiswa(null)}
              className="p-2.5 bg-monday-background border border-monday-border text-monday-gray hover:text-monday-black hover:bg-monday-gray-background rounded-2xl transition-all duration-200"
            >
              <ArrowLeft size={18} />
            </button>
            <div className="flex flex-col gap-[2px]">
              <p className="flex items-center gap-2">
                <span className="font-extrabold text-2xl text-monday-black">
                  Detail Mahasiswa
                </span>
              </p>
              <p className="font-semibold text-sm text-monday-gray">
                Informasi lengkap profil dan akademik mahasiswa.
              </p>
            </div>
          </div>
          <div className="flex items-center gap-2">
            <button
              onClick={() => openModal('mahasiswa', 'edit', selectedMahasiswa)}
              className="px-5 py-2.5 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-full font-bold text-sm transition-all duration-200 flex items-center gap-2"
            >
              <Edit size={14} /> Edit Data
            </button>
          </div>
        </div>

        {/* Profile Card */}
        <div className="rounded-2xl border border-monday-border overflow-hidden relative">
          <div className="absolute top-0 right-0 left-0 h-24 bg-gradient-to-r from-monday-blue/15 to-violet-500/10" />

          {/* Profile Info */}
          <div className="px-8 pb-8 mt-8 relative z-10 ">
            <div className="flex items-end gap-6 mb-6">
              <StudentPhoto mahasiswa={detailData.mahasiswa} size="lg" />
              <div className="flex-1 pb-1">
                <div className="flex items-center gap-3 mb-1">
                  <h2 className="font-extrabold text-2xl text-monday-black drop-shadow-sm">{detailData.mahasiswa.name}</h2>
                  <span className={`px-3 py-1 text-xs font-bold rounded-full border-2 border-white shadow-sm ${getStatusBadge(detailData.mahasiswa.status).replace(/border-[^\s]+/g, '')}`}>
                    {detailData.mahasiswa.status}
                  </span>
                </div>
                <div className="flex flex-wrap items-center gap-3 mt-3">
                  <span className="px-3 py-1.5 bg-monday-blue/10 rounded-xl flex items-center gap-1.5 border border-monday-blue/20">
                    <GraduationCap size={13} className="text-monday-blue" />
                    <span className="text-monday-gray text-xs font-semibold">NIM:</span>
                    <span className="font-bold text-monday-blue text-base leading-none">{detailData.mahasiswa.nim}</span>
                  </span>

                  <span className="px-3 py-1.5 bg-monday-blue/10 text-monday-blue rounded-xl text-xs font-bold flex items-center gap-1.5 border border-monday-blue/20">
                    <Mail size={13} className="text-monday-blue" />
                    <span className="text-monday-gray font-normal">Email:</span>
                    <span className="font-mono text-monday-black select-all">{detailData.user?.email || '-'}</span>
                  </span>

                  <span className="px-3 py-1.5 bg-violet-500/10 text-violet-600 rounded-xl text-xs font-bold flex items-center gap-1.5 border border-violet-500/20">
                    <Lock size={13} className="text-violet-600" />
                    <span className="text-monday-gray font-normal">Sandi:</span>
                    <span className="font-mono text-monday-black">{showPassword ? "password123" : "••••••••"}</span>
                    <button
                      onClick={() => setShowPassword(!showPassword)}
                      className="p-0.5 hover:bg-violet-500/25 rounded transition-colors text-violet-600 cursor-pointer"
                      title={showPassword ? "Sembunyikan Sandi" : "Tampilkan Sandi"}
                    >
                      {showPassword ? <EyeOff size={12} /> : <Eye size={12} />}
                    </button>
                  </span>
                </div>
              </div>
            </div>

            {/* Info Grid */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
              {/* Program Studi */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-monday-blue/10 rounded-xl text-monday-blue flex-shrink-0">
                  <GraduationCap size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Program Studi</p>
                  <p className="font-bold text-sm text-monday-black truncate">{detailData.prodi?.name || '-'}</p>
                  {detailData.prodi?.jenjang && (
                    <p className="text-xs text-monday-gray font-semibold">{detailData.prodi.jenjang}</p>
                  )}
                </div>
              </div>

              {/* Fakultas */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-violet-500/10 rounded-xl text-violet-600 flex-shrink-0">
                  <MapPin size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Fakultas</p>
                  <p className="font-bold text-sm text-monday-black truncate">{detailData.faculties?.name || '-'}</p>
                </div>
              </div>

              {/* Tahun Masuk */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-amber-500/10 rounded-xl text-amber-600 flex-shrink-0">
                  <Calendar size={18} />
                </div>
                <div>
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Tahun Masuk</p>
                  <p className="font-bold text-sm text-monday-black">{detailData.mahasiswa.enrollment_year}</p>
                </div>
              </div>

              {/* Dosen PA */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-emerald-500/10 rounded-xl text-emerald-600 flex-shrink-0">
                  <Users size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Dosen Wali (PA)</p>
                  <p className="font-bold text-sm text-monday-black truncate">{detailData.dosen?.name || '-'}</p>
                  {detailData.dosen?.nidn && (
                    <p className="text-xs text-monday-gray font-semibold">NIDN: {detailData.dosen.nidn}</p>
                  )}
                </div>
              </div>
            </div>

            {/* Academic Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-5 gap-4">
              <div className="rounded-2xl bg-gradient-to-br from-monday-blue/10 to-monday-blue/5 border border-monday-blue/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-monday-blue mb-1">
                  <BookOpen size={16} />
                  <span className="font-extrabold text-2xl">{detailData.totalMk}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Mata Kuliah</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 border border-emerald-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-emerald-600 mb-1">
                  <Award size={16} />
                  <span className="font-extrabold text-2xl">{detailData.totalSks}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Total SKS</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-500/5 border border-violet-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-violet-600 mb-1">
                  <Clock size={16} />
                  <span className="font-extrabold text-2xl">{detailData.semesterCount}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Semester Ditempuh</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-amber-500/10 to-amber-500/5 border border-amber-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-amber-600 mb-1">
                  <TrendingUp size={16} />
                  <span className="font-extrabold text-2xl">{detailData.avgNilai ?? '-'}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Rata-rata Nilai</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-rose-500/10 to-rose-500/5 border border-rose-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-rose-600 mb-1">
                  <ShieldCheck size={16} />
                  <span className="font-extrabold text-2xl">{detailData.ipk ?? '-'}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">IPK</p>
              </div>
            </div>
          </div>
        </div>

        {/* Course History Table */}
        {detailData.courseDetails.length > 0 && (
          <div className="flex flex-col gap-3">
            <h3 className="font-extrabold text-lg text-monday-black flex items-center gap-2">
              <BookOpen size={20} className="text-monday-blue" />
              Riwayat Mata Kuliah
            </h3>
            <div className="border border-monday-border rounded-2xl overflow-hidden bg-white">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
                    <th className="py-3.5 px-5">No</th>
                    <th className="py-3.5 px-5">Mata Kuliah</th>
                    <th className="py-3.5 px-5">Kelas</th>
                    <th className="py-3.5 px-5">Semester</th>
                    <th className="py-3.5 px-5 text-center">SKS</th>
                    <th className="py-3.5 px-5 text-center">Nilai</th>
                    <th className="py-3.5 px-5 text-center">Huruf</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-monday-border text-sm text-monday-black">
                  {detailData.courseDetails.map((cd, index) => (
                    <tr key={cd.km.id} className="hover:bg-monday-gray-background/30 transition-colors">
                      <td className="py-3 px-5 text-monday-gray font-mono font-semibold text-xs">{index + 1}</td>
                      <td className="py-3 px-5">
                        {cd.mk ? (
                          <div>
                            <span className="font-bold text-monday-black">{cd.mk.name}</span>
                            <span className="ml-2 text-xs text-monday-gray font-semibold">{cd.mk.code}</span>
                          </div>
                        ) : <span className="italic text-monday-gray">-</span>}
                      </td>
                      <td className="py-3 px-5">
                        {cd.kk ? (
                          <span className="px-2 py-0.5 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                            {cd.kk.class_name}
                          </span>
                        ) : '-'}
                      </td>
                      <td className="py-3 px-5">
                        {cd.ta ? (
                          <span className="text-xs font-semibold text-monday-gray">{cd.ta.name}</span>
                        ) : '-'}
                      </td>
                      <td className="py-3 px-5 text-center font-bold">{cd.mk?.sks || '-'}</td>
                      <td className="py-3 px-5 text-center font-bold">
                        {cd.km.final_score !== null && cd.km.final_score !== undefined
                          ? cd.km.final_score
                          : <span className="text-monday-gray font-normal italic text-xs">N/A</span>}
                      </td>
                      <td className="py-3 px-5 text-center">
                        {cd.km.letter_grade ? (
                          <span className={`px-2.5 py-1 text-xs font-bold rounded-lg border ${getGradeBadgeClass(cd.km.letter_grade)}`}>
                            {cd.km.letter_grade}
                          </span>
                        ) : (
                          <span className="text-monday-gray text-xs italic">N/A</span>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {detailData.courseDetails.length === 0 && (
          <div className="rounded-2xl border border-dashed border-monday-border bg-monday-background/30 py-12 flex flex-col items-center gap-2">
            <BookOpen size={32} className="text-monday-gray/40" />
            <p className="text-monday-gray font-semibold text-sm">Belum ada data mata kuliah yang diambil mahasiswa ini.</p>
          </div>
        )}
      </div>
    );
  }

  // ─── MAIN TABLE VIEW ───────────────────────────────────────────────
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <GraduationCap className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Data Mahasiswa
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data mahasiswa, program studi, dan dosen wali akademik mereka. Total: {students.length} mahasiswa terdaftar.
          </p>
        </div>
        <button
          onClick={() => openModal('mahasiswa', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Mahasiswa <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input
            type="text"
            placeholder="Cari mahasiswa..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 bg-monday-background border border-monday-border rounded-2xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black transition-300"
          />
        </div>
      </div>

      <div className="border border-monday-border rounded-2xl overflow-hidden bg-white">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
              <th className="py-4 px-6">No</th>
              <th className="py-4 px-6">Mahasiswa</th>
              <th className="py-4 px-6">Email</th>
              <th className="py-4 px-6">Program Studi</th>
              <th className="py-4 px-6">Dosen Wali</th>
              <th className="py-4 px-6 text-center">Status</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {paginatedItems.map((m, index) => {
              const prObj = studyPrograms.find(p => p.id === m.study_program_id);
              const dosObj = lecturers.find(d => d.id === m.academic_advisor_id);
              const uObj = users.find(u => u.id === m.user_id);

              return (
                <tr key={m.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{startIndex + index + 1}</td>
                  <td className="py-3.5 px-6">
                    <div className="flex items-center gap-3">
                      <StudentPhoto mahasiswa={m} size="sm" />
                      <div>
                        <p className="font-bold text-monday-black">{m.name}</p>
                        <p className="text-xs font-bold text-monday-blue">{m.nim}</p>
                      </div>
                    </div>
                  </td>
                  <td className="py-3.5 px-6 font-semibold text-monday-gray font-mono">
                    {uObj ? uObj.email : '-'}
                  </td>
                  <td className="py-3.5 px-6">
                    {prObj ? (
                      <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-xl text-xs font-bold text-monday-gray">
                        {prObj.name}
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 font-medium text-monday-gray">
                    {dosObj ? dosObj.name : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-center">
                    <span className={`px-2.5 py-1 text-xs font-bold rounded-full border ${getStatusBadge(m.status)}`}>
                      {m.status}
                    </span>
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button
                        onClick={() => setSelectedMahasiswa(m)}
                        className="px-3.5 py-1.5 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-xl font-bold text-xs transition-all duration-200 flex items-center gap-1.5"
                      >
                        <Eye size={13} />
                        Detail
                      </button>
                      <button
                        onClick={() => openModal('mahasiswa', 'edit', m)}
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Edit size={16} />
                      </button>
                      <button
                        onClick={() => handleDeleteItem('mahasiswa', m.id)}
                        className="p-1.5 text-monday-gray hover:text-monday-red hover:bg-monday-red/10 rounded-xl transition-300"
                      >
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {/* Pagination Controls */}
      {totalPages > 1 && (
        <div className="flex items-center justify-between pt-4 border-t border-monday-border mt-2">
          <p className="text-sm font-semibold text-monday-gray">
            Menampilkan <span className="text-monday-black font-bold">{totalItems === 0 ? 0 : startIndex + 1}</span> sampai <span className="text-monday-black font-bold">{endIndex}</span> dari <span className="text-monday-black font-bold">{totalItems}</span> mahasiswa
          </p>
          <div className="flex items-center gap-2">
            <button
              onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
              disabled={currentPage === 1}
              className="p-2 border border-monday-border rounded-xl text-monday-gray hover:text-monday-black hover:bg-monday-gray-background disabled:opacity-50 disabled:pointer-events-none transition-300"
            >
              <ChevronLeft size={16} />
            </button>

            <div className="flex items-center gap-1.5">
              {getPageNumbers().map((page, index) => (
                page === '...' ? (
                  <span key={`ellipsis-${index}`} className="px-2 text-monday-gray font-bold text-xs">...</span>
                ) : (
                  <button
                    key={page}
                    onClick={() => setCurrentPage(page)}
                    className={`px-3 py-1 rounded-xl font-bold text-xs transition-300 ${currentPage === page
                      ? 'bg-monday-blue text-white shadow-md shadow-monday-blue/15'
                      : 'border border-monday-border text-monday-gray hover:text-monday-black hover:bg-monday-gray-background'
                      }`}
                  >
                    {page}
                  </button>
                )
              ))}
            </div>

            <button
              onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
              disabled={currentPage === totalPages}
              className="p-2 border border-monday-border rounded-xl text-monday-gray hover:text-monday-black hover:bg-monday-gray-background disabled:opacity-50 disabled:pointer-events-none transition-300"
            >
              <ChevronRight size={16} />
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
