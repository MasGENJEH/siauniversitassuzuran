import React, { useState, useEffect, useMemo } from 'react';
import { Edit3, Plus, Search, Edit, Trash2, ChevronDown, ChevronLeft, ChevronRight, Eye, X, BookOpen, Award, TrendingUp, ArrowLeft } from 'lucide-react';

export default function KelasMahasiswaTab({
  user,
  kelasMahasiswas,
  students,
  kelasKuliahs,
  mataKuliahs,
  lecturers = [],
  dosenPengampus = [],
  tahunAkademiks = [],
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedMahasiswa, setSelectedMahasiswa] = useState(null);
  const [viewingClass, setViewingClass] = useState(null);
  const itemsPerPage = 10;

  const isMahasiswa = (user?.roles || []).some(r => r.name === 'mahasiswa');
  const myMahasiswa = useMemo(() => {
    if (!isMahasiswa || !user) return null;
    return students.find(m => m.user_id === user.id) || null;
  }, [isMahasiswa, user, students]);

  // Reset to page 1 when search query changes
  useEffect(() => {
    setCurrentPage(1);
  }, [searchQuery]);

  // Build student-centric data: group kelasMahasiswas by mahasiswa
  const studentSummaries = useMemo(() => {
    const activeSemester = tahunAkademiks.find(ta => ta.status) || null;
    const studentMap = {};

    kelasMahasiswas.forEach(km => {
      if (!studentMap[km.student_id]) {
        studentMap[km.student_id] = [];
      }
      studentMap[km.student_id].push(km);
    });

    const getGradeWeight = (letter) => {
      const char = (letter || '').toUpperCase().trim();
      switch (char) {
        case 'A': return 4.0;
        case 'B': return 3.0;
        case 'C': return 2.0;
        case 'D': return 1.0;
        case 'E': return 0.0;
        default: return 0.0;
      }
    };

    // Build summaries
    return Object.entries(studentMap).map(([mahasiswaId, enrollments]) => {
      const mhs = students.find(m => m.id === Number(mahasiswaId));
      if (!mhs) return null;

      let totalSksDiambil = 0;
      let totalSksLulus = 0;
      let sksDiambilSemesterIni = 0;

      let gradedSksSemester = 0;
      let weightedSumSemester = 0;
      let gradedSksTotal = 0;
      let weightedSumTotal = 0;

      enrollments.forEach(km => {
        const kk = kelasKuliahs.find(k => k.id === km.course_class_id);
        const mk = kk ? mataKuliahs.find(m => m.id === kk.course_id) : null;
        if (!mk) return;

        const sks = Number(mk.sks || 0);
        totalSksDiambil += sks;

        const isCurrentSemester = activeSemester && kk.academic_year_id === activeSemester.id;

        if (isCurrentSemester) {
          sksDiambilSemesterIni += sks;
          if (km.letter_grade !== null && km.letter_grade !== undefined) {
            gradedSksSemester += sks;
            weightedSumSemester += sks * getGradeWeight(km.letter_grade);
          }
        }

        if (km.letter_grade !== null && km.letter_grade !== undefined) {
          gradedSksTotal += sks;
          weightedSumTotal += sks * getGradeWeight(km.letter_grade);
          if (km.letter_grade.toUpperCase().trim() !== 'E') {
            totalSksLulus += sks;
          }
        }
      });

      const ips = gradedSksSemester > 0 ? (weightedSumSemester / gradedSksSemester).toFixed(2) : '0.00';
      const ipk = gradedSksTotal > 0 ? (weightedSumTotal / gradedSksTotal).toFixed(2) : '0.00';

      return {
        mahasiswa: mhs,
        enrollments,
        totalMk: enrollments.length,
        totalSks: totalSksDiambil,
        sksDiambil: totalSksDiambil,
        sksLulus: totalSksLulus,
        sksDiambilSemesterIni,
        ips,
        ipk
      };
    }).filter(Boolean);
  }, [kelasMahasiswas, students, kelasKuliahs, mataKuliahs, tahunAkademiks]);

  // Filter students
  const filteredStudents = studentSummaries.filter(s =>
    s.mahasiswa.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    s.mahasiswa.nim.toLowerCase().includes(searchQuery.toLowerCase())
  );

  // Pagination
  const totalItems = filteredStudents.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
  const paginatedStudents = filteredStudents.slice(startIndex, endIndex);

  // Detail view: get KRS entries for selected mahasiswa
  const selectedStudentData = useMemo(() => {
    const activeMhs = isMahasiswa ? myMahasiswa : selectedMahasiswa;
    if (!activeMhs) return null;
    const summary = studentSummaries.find(s => s.mahasiswa.id === activeMhs.id);
    if (summary) return summary;
    return {
      mahasiswa: activeMhs,
      enrollments: [],
      totalMk: 0,
      totalSks: 0,
      avgNilai: null,
      gradedCount: 0
    };
  }, [isMahasiswa, myMahasiswa, selectedMahasiswa, studentSummaries]);

  // Grade letter badge color helper
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

  const activeMahasiswa = isMahasiswa ? myMahasiswa : selectedMahasiswa;

  // ─── DETAIL VIEW ───────────────────────────────────────────────────
  if (activeMahasiswa && selectedStudentData) {
    return (
      <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
        {/* Back Button & Header */}
        <div className="flex items-center justify-between pb-4 border-b border-monday-border">
          <div className="flex items-center gap-4">
            {!isMahasiswa && (
              <button
                onClick={() => setSelectedMahasiswa(null)}
                className="p-2.5 bg-monday-background border border-monday-border text-monday-gray hover:text-monday-black hover:bg-monday-gray-background rounded-2xl transition-all duration-200"
              >
                <ArrowLeft size={18} />
              </button>
            )}
            <div className="flex flex-col gap-[2px]">
              <p className="flex items-center gap-2">
                <span className="font-extrabold text-2xl text-monday-black">
                  Detail KRS Mahasiswa
                </span>
              </p>
              <p className="font-semibold text-sm text-monday-gray">
                Rincian mata kuliah yang diambil oleh mahasiswa ini.
              </p>
            </div>
          </div>
          <button
            onClick={() => openModal('kelasMahasiswa', 'create', { student_id: activeMahasiswa.id })}
            className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
          >
            Daftarkan Kelas (KRS) <Plus size={16} />
          </button>
        </div>

        {/* Student Info Card */}
        <div className="rounded-2xl border border-monday-border bg-gradient-to-br from-monday-blue/5 to-transparent p-5">
          <div className="flex items-start justify-between">
            <div className="flex items-center gap-4">
              <div className="w-14 h-14 rounded-2xl bg-monday-blue/10 border border-monday-blue/20 flex items-center justify-center text-monday-blue font-extrabold text-xl">
                {activeMahasiswa.name.charAt(0).toUpperCase()}
              </div>
              <div>
                <h3 className="font-extrabold text-lg text-monday-black">{activeMahasiswa.name}</h3>
                <p className="font-bold text-sm text-monday-blue">{activeMahasiswa.nim}</p>
              </div>
            </div>

            {/* Stats */}
            <div className="flex items-center gap-6">
              <div className="flex flex-col items-center gap-1 px-4">
                <div className="flex items-center gap-1.5 text-monday-blue">
                  <BookOpen size={16} />
                  <span className="font-extrabold text-xl">{selectedStudentData.totalMk}</span>
                </div>
                <span className="text-xs font-bold text-monday-gray">Mata Kuliah</span>
              </div>
              <div className="w-px h-10 bg-monday-border" />
              <div className="flex flex-col items-center gap-1 px-4">
                <div className="flex items-center gap-1.5 text-emerald-600">
                  <Award size={16} />
                  <span className="font-extrabold text-xl">
                    {selectedStudentData.sksLulus} / {selectedStudentData.sksDiambil}
                  </span>
                </div>
                <span className="text-xs font-bold text-monday-gray">SKS Lulus / Diambil</span>
              </div>
              <div className="w-px h-10 bg-monday-border" />
              <div className="flex flex-col items-center gap-1 px-4">
                <div className="flex items-center gap-1.5 text-amber-600">
                  <TrendingUp size={16} />
                  <span className="font-extrabold text-xl">
                    {selectedStudentData.ipk} <span className="text-xs text-monday-gray font-normal">({selectedStudentData.ips} IPS)</span>
                  </span>
                </div>
                <span className="text-xs font-bold text-monday-gray">IPK (Semester IPS)</span>
              </div>
            </div>
          </div>
        </div>

        {/* KRS Table */}
        <div className="border border-monday-border rounded-2xl overflow-hidden bg-white">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
                <th className="py-4 px-6">No</th>
                <th className="py-4 px-6">Mata Kuliah</th>
                <th className="py-4 px-6">Kelas</th>
                <th className="py-4 px-6 text-center">SKS</th>
                <th className="py-4 px-6 text-center">Nilai Angka</th>
                <th className="py-4 px-6 text-center">Nilai Huruf</th>
                <th className="py-4 px-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-monday-border text-sm text-monday-black">
              {selectedStudentData.enrollments.map((km, index) => {
                const kkObj = kelasKuliahs.find(k => k.id === km.course_class_id);
                const mkObj = kkObj ? mataKuliahs.find(m => m.id === kkObj.course_id) : null;

                return (
                  <tr key={km.id} className="hover:bg-monday-gray-background/30 transition-colors">
                    <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                    <td className="py-3.5 px-6">
                      {mkObj ? (
                        <div className="flex flex-col">
                          <span className="font-bold text-monday-black">{mkObj.name}</span>
                          <span className="text-xs font-semibold text-monday-gray">{mkObj.code}</span>
                        </div>
                      ) : <span className="text-monday-gray italic">-</span>}
                    </td>
                    <td className="py-3.5 px-6">
                      {kkObj ? (
                        <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                          {kkObj.class_name}
                        </span>
                      ) : '-'}
                    </td>
                    <td className="py-3.5 px-6 text-center font-bold">
                      {mkObj && mkObj.sks ? mkObj.sks : '-'}
                    </td>
                    <td className="py-3.5 px-6 text-center font-bold">
                      {km.final_score !== null && km.final_score !== undefined
                        ? km.final_score
                        : <span className="text-monday-gray font-normal italic">Belum Dinilai</span>}
                    </td>
                    <td className="py-3.5 px-6 text-center">
                      {km.letter_grade ? (
                        <span className={`px-2.5 py-1 text-xs font-bold rounded-lg border ${getGradeBadgeClass(km.letter_grade)}`}>
                          {km.letter_grade}
                        </span>
                      ) : (
                        <span className="text-monday-gray text-xs italic">N/A</span>
                      )}
                    </td>
                    <td className="py-3.5 px-6 text-right">
                      <div className="flex items-center justify-end gap-2">
                        {isMahasiswa ? (
                          <button
                            onClick={() => {
                              const kkObj = kelasKuliahs.find(k => k.id === km.course_class_id);
                              const mkObj = kkObj ? mataKuliahs.find(m => m.id === kkObj.course_id) : null;
                              const teachingLinks = dosenPengampus.filter(dp => dp.course_class_id === km.course_class_id);
                              const lecturers = teachingLinks.map(dp => lecturers.find(d => d.id === dp.lecturer_id)).filter(Boolean);
                              setViewingClass({ kk: kkObj, mk: mkObj, lecturers });
                            }}
                            className="px-3.5 py-1.5 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-xl font-bold text-xs transition-all duration-200 flex items-center gap-1.5 ml-auto"
                          >
                            <Eye size={13} />
                            Detail Kelas
                          </button>
                        ) : (
                          <>
                            <button
                              onClick={() => openModal('kelasMahasiswa', 'edit', km)}
                              className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                            >
                              <Edit size={16} />
                            </button>
                            <button
                              onClick={() => handleDeleteItem('kelasMahasiswa', km.id)}
                              className="p-1.5 text-monday-gray hover:text-monday-red hover:bg-monday-red/10 rounded-xl transition-300"
                            >
                              <Trash2 size={16} />
                            </button>
                          </>
                        )}
                      </div>
                    </td>
                  </tr>
                );
              })}
              {selectedStudentData.enrollments.length === 0 && (
                <tr>
                  <td colSpan={7} className="py-12 text-center text-monday-gray font-semibold italic">
                    Belum ada mata kuliah yang diambil mahasiswa ini.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
        {/* Class Detail Modal for Students */}
        {viewingClass && (
          <div className="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-monday-black/40 backdrop-blur-sm animate-fade-in">
            <div className="bg-white rounded-3xl p-6 shadow-2xl border border-monday-border max-w-md w-full flex flex-col gap-5">
              <div className="flex items-center justify-between pb-3 border-b border-monday-border">
                <h3 className="font-extrabold text-lg text-monday-black">Detail Kelas Kuliah</h3>
                <button 
                  onClick={() => setViewingClass(null)}
                  className="p-1 text-monday-gray hover:text-monday-black hover:bg-monday-gray-background rounded-lg transition-300"
                >
                  <X size={20} />
                </button>
              </div>
              <div className="space-y-4 text-left">
                <div>
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Mata Kuliah</p>
                  <p className="font-bold text-sm text-monday-black">{viewingClass.mk?.name} ({viewingClass.mk?.code})</p>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">SKS</p>
                    <p className="font-bold text-sm text-monday-black">{viewingClass.mk?.sks} SKS</p>
                  </div>
                  <div>
                    <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Kelas</p>
                    <p className="font-bold text-sm text-monday-black">{viewingClass.kk?.class_name}</p>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Jadwal</p>
                    <p className="font-bold text-sm text-monday-black">{viewingClass.kk?.day}, {viewingClass.kk?.start_time?.substring(0, 5)} - {viewingClass.kk?.end_time?.substring(0, 5)}</p>
                  </div>
                  <div>
                    <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Ruangan</p>
                    <p className="font-bold text-sm text-monday-black">{viewingClass.kk?.room}</p>
                  </div>
                </div>
                <div>
                  <p className="text-[11px] font-bold text-monday-gray uppercase tracking-wider mb-0.5">Dosen Pengampu</p>
                  <div className="space-y-1">
                    {viewingClass.lecturers && viewingClass.lecturers.length > 0 ? (
                      viewingClass.lecturers.map(d => (
                        <p key={d.id} className="text-sm font-semibold text-monday-black">• {d.name}</p>
                      ))
                    ) : (
                      <p className="text-sm text-monday-gray italic">Belum ditentukan</p>
                    )}
                  </div>
                </div>
              </div>
              <div className="flex justify-end pt-3 border-t border-monday-border">
                <button
                  onClick={() => setViewingClass(null)}
                  className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    );
  }

  // ─── MAIN VIEW: STUDENT LIST ─────────────────────────────────────
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <Edit3 className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              KRS & Transkrip Nilai KHS Mahasiswa
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Daftarkan mahasiswa ke kelas kuliah (Rencana Studi / KRS) dan pantau hasil akhir studi (Hasil Studi / KHS). Total: {filteredStudents.length} mahasiswa.
          </p>
        </div>
        <button
          onClick={() => openModal('kelasMahasiswa', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Daftarkan Kelas (KRS) <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input
            type="text"
            placeholder="Cari name / NIM mahasiswa..."
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
              <th className="py-4 px-6">NIM</th>
              <th className="py-4 px-6">Nama Mahasiswa</th>
              <th className="py-4 px-6 text-center">Jumlah MK</th>
              <th className="py-4 px-6 text-center">SKS Lulus / Diambil</th>
              <th className="py-4 px-6 text-center">IPK</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {paginatedStudents.map((s, index) => (
              <tr key={s.mahasiswa.id} className="hover:bg-monday-gray-background/30 transition-colors">
                <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{startIndex + index + 1}</td>
                <td className="py-3.5 px-6 font-bold text-monday-blue">{s.mahasiswa.nim}</td>
                <td className="py-3.5 px-6 font-semibold">{s.mahasiswa.name}</td>
                <td className="py-3.5 px-6 text-center">
                  <span className="px-2.5 py-1 bg-monday-blue/10 text-monday-blue border border-monday-blue/15 rounded-lg text-xs font-bold">
                    {s.totalMk} MK
                  </span>
                </td>
                <td className="py-3.5 px-6 text-center font-bold">{s.sksLulus} / {s.sksDiambil} SKS</td>
                <td className="py-3.5 px-6 text-center">
                  <span className="font-bold text-monday-black">{s.ipk}</span>
                </td>
                <td className="py-3.5 px-6 text-right">
                  <button
                    onClick={() => setSelectedMahasiswa(s.mahasiswa)}
                    className="px-4 py-2 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-xl font-bold text-xs transition-all duration-200 flex items-center gap-1.5 ml-auto"
                  >
                    <Eye size={14} />
                    Detail
                  </button>
                </td>
              </tr>
            ))}
            {paginatedStudents.length === 0 && (
              <tr>
                <td colSpan={7} className="py-12 text-center text-monday-gray font-semibold italic">
                  {searchQuery ? 'Tidak ada mahasiswa yang cocok dengan pencarian.' : 'Belum ada data KRS mahasiswa.'}
                </td>
              </tr>
            )}
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
              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  onClick={() => setCurrentPage(page)}
                  className={`px-3 py-1 rounded-xl font-bold text-xs transition-300 ${
                    currentPage === page
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
