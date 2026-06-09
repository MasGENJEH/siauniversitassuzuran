import React, { useState, useEffect, useMemo } from 'react';
import { Users, Plus, Search, Edit, Trash2, ChevronLeft, ChevronRight, Eye, EyeOff, ArrowLeft, User, BookOpen, Award, Calendar, Clock, MapPin, Mail, Lock } from 'lucide-react';

export default function DosenTab({
  dosens,
  users,
  mahasiswas = [],
  dosenPengampus = [],
  kelasKuliahs = [],
  mataKuliahs = [],
  tahunAkademiks = [],
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedDosen, setSelectedDosen] = useState(null);
  const [showPassword, setShowPassword] = useState(false);
  const itemsPerPage = 10;

  // Reset to page 1 when search query changes
  useEffect(() => {
    setCurrentPage(1);
  }, [searchQuery]);

  // Reset showPassword when selectedDosen changes
  useEffect(() => {
    setShowPassword(false);
  }, [selectedDosen]);

  // Filter items based on search query
  const filteredItems = dosens.filter(d =>
    d.nama.toLowerCase().includes(searchQuery.toLowerCase()) ||
    d.nidn.toLowerCase().includes(searchQuery.toLowerCase())
  );

  // Pagination bounds & slice
  const totalItems = filteredItems.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
  const paginatedItems = filteredItems.slice(startIndex, endIndex);

  // Compute detail data for selected dosen
  const detailData = useMemo(() => {
    if (!selectedDosen) return null;

    const dsn = selectedDosen;
    const userObj = users.find(u => u.id === dsn.id_user);

    // Get all advisee students (mahasiswa bimbingan PA)
    const advisees = mahasiswas.filter(m => m.id_dosen_pa === dsn.id);

    // Get all classes taught by this dosen (dosen_pengampus)
    const teachingLinks = dosenPengampus.filter(dp => dp.id_dosen === dsn.id);
    const classesTaught = [];
    let totalTeachingSks = 0;

    teachingLinks.forEach(dp => {
      const kk = kelasKuliahs.find(k => k.id === dp.id_kelas);
      if (kk) {
        const mk = mataKuliahs.find(m => m.id === kk.id_mk);
        const ta = tahunAkademiks.find(t => t.id === kk.id_ta);
        if (mk && mk.sks) {
          totalTeachingSks += Number(mk.sks);
        }
        classesTaught.push({ dp, kk, mk, ta });
      }
    });

    return {
      dosen: dsn,
      user: userObj,
      advisees,
      classesTaught,
      totalClasses: classesTaught.length,
      totalAdvisees: advisees.length,
      totalTeachingSks
    };
  }, [selectedDosen, users, mahasiswas, dosenPengampus, kelasKuliahs, mataKuliahs, tahunAkademiks]);

  // Photo component with fallback
  const DosenPhoto = ({ dosen, size = 'lg' }) => {
    const [imgError, setImgError] = useState(false);
    const sizeClasses = size === 'lg' ? 'w-28 h-28' : size === 'md' ? 'w-16 h-16' : 'w-10 h-10';
    const iconSize = size === 'lg' ? 48 : size === 'md' ? 24 : 16;

    const fotoUrl = dosen.foto ? `/storage/${dosen.foto}` : null;

    if (fotoUrl && !imgError) {
      return (
        <div className={`${sizeClasses} rounded-2xl overflow-hidden border-2 border-monday-blue/20 shadow-lg shadow-monday-blue/10 flex-shrink-0 ${size === 'lg' ? 'border-4 border-white bg-white relative z-20 shadow-xl' : ''}`}>
          <img
            src={fotoUrl}
            alt={dosen.nama}
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
  if (selectedDosen && detailData) {
    return (
      <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm animate-fade-in">
        {/* Back Button & Header */}
        <div className="flex items-center justify-between pb-4 border-b border-monday-border">
          <div className="flex items-center gap-4">
            <button
              onClick={() => setSelectedDosen(null)}
              className="p-2.5 bg-monday-background border border-monday-border text-monday-gray hover:text-monday-black hover:bg-monday-gray-background rounded-2xl transition-all duration-200"
            >
              <ArrowLeft size={18} />
            </button>
            <div className="flex flex-col gap-[2px]">
              <p className="flex items-center gap-2">
                <span className="font-extrabold text-2xl text-monday-black">
                  Detail Dosen
                </span>
              </p>
              <p className="font-semibold text-sm text-monday-gray">
                Informasi lengkap profil, jadwal mengajar, dan bimbingan akademik.
              </p>
            </div>
          </div>
          <div className="flex items-center gap-2">
            <button
              onClick={() => openModal('dosen', 'edit', selectedDosen)}
              className="px-5 py-2.5 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-full font-bold text-sm transition-all duration-200 flex items-center gap-2"
            >
              <Edit size={14} /> Edit Data
            </button>
          </div>
        </div>

        {/* Profile Card */}
        <div className="rounded-2xl border border-monday-border overflow-hidden relative">
          <div className="absolute top-0 right-0 left-0 h-24 bg-gradient-to-r from-monday-blue/15 to-violet-500/10 z-0" />

          {/* Profile Info */}
          <div className="px-8 pb-8 mt-8 relative z-10">
            <div className="flex items-end gap-6 mb-6">
              <DosenPhoto dosen={detailData.dosen} size="lg" />
              <div className="flex-1 pb-1">
                <div className="flex items-center gap-3 mb-1">
                  <h2 className="font-extrabold text-2xl text-monday-black drop-shadow-sm">{detailData.dosen.nama}</h2>
                </div>
                <div className="flex flex-wrap items-center gap-3 mt-3">
                  <span className="px-3 py-1.5 bg-monday-blue/10 rounded-xl flex items-center gap-1.5 border border-monday-blue/20">
                    <Users size={13} className="text-monday-blue" />
                    <span className="text-monday-gray text-xs font-semibold">NIDN:</span>
                    <span className="font-bold text-monday-blue text-base leading-none">{detailData.dosen.nidn}</span>
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
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              {/* Akun User */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-violet-500/10 rounded-xl text-violet-600 flex-shrink-0">
                  <User size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Akun User</p>
                  {detailData.user ? (
                    <>
                      <p className="font-bold text-sm text-monday-black truncate">{detailData.user.username}</p>
                      <p className="text-xs text-monday-gray font-semibold">{detailData.user.email}</p>
                    </>
                  ) : (
                    <p className="text-sm text-monday-gray italic font-semibold">Belum ditautkan ke akun user</p>
                  )}
                </div>
              </div>

              {/* Status */}
              <div className="rounded-2xl bg-monday-background/60 border border-monday-border p-4 flex items-start gap-3 shadow-sm hover:shadow-md transition-all duration-200">
                <div className="p-2 bg-emerald-500/10 rounded-xl text-emerald-600 flex-shrink-0">
                  <Award size={18} />
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Status Pengajar</p>
                  <p className="font-bold text-sm text-monday-black">Dosen Pengajar</p>
                  <p className="text-xs text-monday-gray font-semibold">Aktif Mengajar</p>
                </div>
              </div>
            </div>

            {/* Academic Stats */}
            <div className="grid grid-cols-3 gap-4">
              <div className="rounded-2xl bg-gradient-to-br from-monday-blue/10 to-monday-blue/5 border border-monday-blue/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-monday-blue mb-1">
                  <BookOpen size={16} />
                  <span className="font-extrabold text-2xl">{detailData.totalClasses}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Kelas Diajar</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 border border-emerald-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-emerald-600 mb-1">
                  <Award size={16} />
                  <span className="font-extrabold text-2xl">{detailData.totalTeachingSks}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Total SKS Diajar</p>
              </div>

              <div className="rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-500/5 border border-violet-500/15 p-4 text-center shadow-sm hover:shadow-md transition-all duration-200">
                <div className="flex items-center justify-center gap-1.5 text-violet-600 mb-1">
                  <Users size={16} />
                  <span className="font-extrabold text-2xl">{detailData.totalAdvisees}</span>
                </div>
                <p className="text-xs font-bold text-monday-gray">Mahasiswa Bimbingan PA</p>
              </div>
            </div>
          </div>
        </div>

        {/* Classes Taught */}
        <div className="flex flex-col gap-3">
          <h3 className="font-extrabold text-lg text-monday-black flex items-center gap-2">
            <BookOpen size={20} className="text-monday-blue" />
            Daftar Kelas Kuliah Diajar
          </h3>
          {detailData.classesTaught.length > 0 ? (
            <div className="border border-monday-border rounded-2xl overflow-hidden bg-white">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
                    <th className="py-3.5 px-5">No</th>
                    <th className="py-3.5 px-5">Mata Kuliah</th>
                    <th className="py-3.5 px-5">Kelas</th>
                    <th className="py-3.5 px-5">Tahun Akademik</th>
                    <th className="py-3.5 px-5">Jadwal & Ruangan</th>
                    <th className="py-3.5 px-5 text-center">SKS</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-monday-border text-sm text-monday-black">
                  {detailData.classesTaught.map((ct, index) => (
                    <tr key={ct.dp.id} className="hover:bg-monday-gray-background/30 transition-colors">
                      <td className="py-3 px-5 text-monday-gray font-mono font-semibold text-xs">{index + 1}</td>
                      <td className="py-3 px-5">
                        {ct.mk ? (
                          <div>
                            <span className="font-bold text-monday-black">{ct.mk.nama_mk}</span>
                            <span className="ml-2 text-xs text-monday-gray font-semibold">{ct.mk.kode_mk}</span>
                          </div>
                        ) : <span className="italic text-monday-gray">-</span>}
                      </td>
                      <td className="py-3 px-5">
                        {ct.kk ? (
                          <span className="px-2 py-0.5 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                            {ct.kk.nama_kelas}
                          </span>
                        ) : '-'}
                      </td>
                      <td className="py-3 px-5">
                        {ct.ta ? (
                          <span className="text-xs font-semibold text-monday-gray">{ct.ta.nama_ta}</span>
                        ) : '-'}
                      </td>
                      <td className="py-3 px-5 text-xs text-monday-gray font-semibold">
                        {ct.kk ? `${ct.kk.hari}, ${ct.kk.jam_mulai.substring(0, 5)} - ${ct.kk.jam_selesai.substring(0, 5)} (${ct.kk.ruangan})` : '-'}
                      </td>
                      <td className="py-3 px-5 text-center font-bold">{ct.mk?.sks || '-'}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ) : (
            <div className="rounded-2xl border border-dashed border-monday-border bg-monday-background/30 py-10 flex flex-col items-center gap-2">
              <BookOpen size={32} className="text-monday-gray/40" />
              <p className="text-monday-gray font-semibold text-sm">Dosen ini belum ditugaskan mengajar kelas manapun.</p>
            </div>
          )}
        </div>

        {/* Advisees */}
        <div className="flex flex-col gap-3">
          <h3 className="font-extrabold text-lg text-monday-black flex items-center gap-2">
            <Users size={20} className="text-emerald-600" />
            Daftar Mahasiswa Bimbingan Akademik (PA)
          </h3>
          {detailData.advisees.length > 0 ? (
            <div className="border border-monday-border rounded-2xl overflow-hidden bg-white">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
                    <th className="py-3.5 px-5">No</th>
                    <th className="py-3.5 px-5">NIM</th>
                    <th className="py-3.5 px-5">Nama Mahasiswa</th>
                    <th className="py-3.5 px-5">Tahun Masuk</th>
                    <th className="py-3.5 px-5 text-center">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-monday-border text-sm text-monday-black">
                  {detailData.advisees.map((mhs, index) => (
                    <tr key={mhs.id} className="hover:bg-monday-gray-background/30 transition-colors">
                      <td className="py-3 px-5 text-monday-gray font-mono font-semibold text-xs">{index + 1}</td>
                      <td className="py-3 px-5 font-bold text-monday-blue font-mono">{mhs.nim}</td>
                      <td className="py-3 px-5 font-semibold">{mhs.nama}</td>
                      <td className="py-3 px-5 text-xs text-monday-gray font-semibold">{mhs.tahun_masuk}</td>
                      <td className="py-3 px-5 text-center">
                        <span className={`px-2 py-0.5 text-xs font-bold rounded-full border ${mhs.status_mahasiswa === 'AKTIF' ? 'bg-emerald-500/15 text-emerald-700 border-emerald-500/20' :
                            mhs.status_mahasiswa === 'CUTI' ? 'bg-amber-500/15 text-amber-700 border-amber-500/20' :
                              mhs.status_mahasiswa === 'LULUS' ? 'bg-monday-blue/15 text-monday-blue border-monday-blue/20' :
                                'bg-monday-red/15 text-monday-red border-monday-red/20'
                          }`}>
                          {mhs.status_mahasiswa}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ) : (
            <div className="rounded-2xl border border-dashed border-monday-border bg-monday-background/30 py-10 flex flex-col items-center gap-2">
              <Users size={32} className="text-monday-gray/40" />
              <p className="text-monday-gray font-semibold text-sm">Dosen ini belum memiliki mahasiswa bimbingan akademik.</p>
            </div>
          )}
        </div>
      </div>
    );
  }

  // ─── MAIN LIST VIEW ────────────────────────────────────────────────
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <Users className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Data Dosen
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data staf pengajar/dosen dan bimbingan akademik. Total: {dosens.length} dosen terdaftar.
          </p>
        </div>
        <button
          onClick={() => openModal('dosen', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Dosen <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input
            type="text"
            placeholder="Cari dosen..."
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
              <th className="py-4 px-6">Dosen</th>
              <th className="py-4 px-6">Email</th>
              <th className="py-4 px-6 text-center">Kelas Diampu</th>
              <th className="py-4 px-6 text-center">Mahasiswa Wali</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {paginatedItems.map((d, index) => {
              const uObj = users.find(u => u.id === d.id_user);
              const classCount = dosenPengampus.filter(dp => dp.id_dosen === d.id).length;
              const adviseeCount = mahasiswas.filter(m => m.id_dosen_pa === d.id).length;

              return (
                <tr key={d.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{startIndex + index + 1}</td>
                  <td className="py-3.5 px-6">
                    <div className="flex items-center gap-3">
                      <DosenPhoto dosen={d} size="sm" />
                      <div>
                        <p className="font-bold text-monday-black">{d.nama}</p>
                        <p className="text-xs font-bold text-monday-blue">{d.nidn}</p>
                      </div>
                    </div>
                  </td>
                  <td className="py-3.5 px-6 font-semibold text-monday-gray font-mono">
                    {uObj ? uObj.email : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-center">
                    <span className="px-2.5 py-1 bg-monday-blue/10 text-monday-blue border border-monday-blue/15 rounded-lg text-xs font-bold font-mono">
                      {classCount} Kelas
                    </span>
                  </td>
                  <td className="py-3.5 px-6 text-center">
                    <span className="px-2.5 py-1 bg-emerald-500/10 text-emerald-700 border border-emerald-500/15 rounded-lg text-xs font-bold font-mono">
                      {adviseeCount} Mahasiswa
                    </span>
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button
                        onClick={() => setSelectedDosen(d)}
                        className="px-3.5 py-1.5 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-xl font-bold text-xs transition-all duration-200 flex items-center gap-1.5"
                      >
                        <Eye size={13} />
                        Detail
                      </button>
                      <button
                        onClick={() => openModal('dosen', 'edit', d)}
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Edit size={16} />
                      </button>
                      <button
                        onClick={() => handleDeleteItem('dosen', d.id)}
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
            Menampilkan <span className="text-monday-black font-bold">{totalItems === 0 ? 0 : startIndex + 1}</span> sampai <span className="text-monday-black font-bold">{endIndex}</span> dari <span className="text-monday-black font-bold">{totalItems}</span> dosen
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
              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
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
