import React, { useState } from 'react';
import { Briefcase, AlertTriangle, Search, Info, Users, GraduationCap } from 'lucide-react';

export default function LecturerPortalTab({
  user,
  lecturers,
  mataKuliahs,
  students,
  kelasKuliahs,
  kelasMahasiswas,
  dosenActiveClasses,
  dosenAdviseeStudents = [],
  studyPrograms = [],
  loadingPortal,
  selectedDosenForPortal,
  setSelectedDosenForPortal,
  selectedClassForGrades,
  setSelectedClassForGrades,
  enrolledStudentsInClass,
  setEnrolledStudentsInClass,
  updatingGrades,
  setUpdatingGrades,
  fetchLecturerPortalData,
  selectClassForPortalGrades,
  saveStudentGrade
}) {
  const [portalSubTab, setPortalSubTab] = useState('classes'); // 'classes' | 'advisees'
  const [adviseeSearchQuery, setAdviseeSearchQuery] = useState('');
  const [showAttendanceModal, setShowAttendanceModal] = useState(false);

  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm print:border-none print:shadow-none print:bg-white print:p-0">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px] w-full">
          <p className="flex items-center gap-[6px]">
            <Briefcase className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Portal Dosen - Lihat Kelas Diampu & Input Nilai KHS
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Simulasi portal login dosen pada semester aktif. Dosen dapat memantau kelas yang diampunya (baik sebagai dosen tunggal maupun bagian dari team teaching) dan melakukan input nilai akhir mahasiswa.
          </p>
        </div>
      </div>

      {/* Select active lecturer or Dosen profile banner */}
      {user?.roles?.some(r => r.name === 'dosen') ? (
        <div className="p-5 rounded-2xl bg-white border border-monday-border flex flex-col md:flex-row items-center gap-4 print:hidden">
          <div className="flex items-center gap-3">
            <div className="bg-monday-blue/10 p-3 rounded-2xl text-monday-blue">
              <Briefcase size={24} />
            </div>
            <div>
              <p className="text-xs font-bold text-monday-gray uppercase tracking-wider">Dosen Pengampu Aktif</p>
              <h3 className="font-extrabold text-lg text-monday-black mt-0.5">
                {lecturers.find(d => d.user_id === user.id)?.name || user.name}
              </h3>
            </div>
          </div>
          <div className="flex items-center gap-2 p-3 bg-monday-lime-green/20 border border-monday-lime-green/30 rounded-xl text-xs font-semibold text-monday-black md:ml-auto">
            <Info size={16} className="text-monday-black" />
            <span>Terhubung sebagai dosen pengampu aktif. Menampilkan seluruh penugasan kelas semester berjalan.</span>
          </div>
        </div>
      ) : (
        <div className="p-5 rounded-2xl bg-monday-background border border-monday-border flex flex-col md:flex-row items-center gap-4 print:hidden">
          <div className="space-y-1 w-full md:w-auto shrink-0">
            <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Simulasi Login Dosen</label>
            <select 
              value={selectedDosenForPortal}
              onChange={(e) => {
                const val = e.target.value;
                setSelectedDosenForPortal(val);
                fetchLecturerPortalData(val);
              }}
              className="w-80 px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black transition-300 mt-1"
            >
              <option value="">-- Pilih Dosen Pengampu --</option>
              {lecturers.map(d => (
                <option key={d.id} value={d.id}>{d.name} (NIDN: {d.nidn})</option>
              ))}
            </select>
          </div>

          {selectedDosenForPortal && (
            <div className="flex items-center gap-2 p-3 bg-monday-lime-green/20 border border-monday-lime-green/30 rounded-xl text-xs font-semibold text-monday-black md:ml-auto">
              <Info size={16} className="text-monday-black" />
              <span>Terhubung sebagai dosen pengampu aktif. Menampilkan seluruh penugasan kelas semester berjalan.</span>
            </div>
          )}
        </div>
      )}

      {selectedDosenForPortal && (
        <div className="flex border-b border-monday-border print:hidden -mt-2">
          <button
            type="button"
            onClick={() => setPortalSubTab('classes')}
            className={`flex items-center gap-2 px-6 py-3 border-b-2 font-bold text-sm transition-all duration-200 cursor-pointer ${
              portalSubTab === 'classes'
                ? 'border-monday-blue text-monday-blue bg-monday-blue/5'
                : 'border-transparent text-monday-gray hover:text-monday-black hover:bg-monday-gray-background/20'
            }`}
          >
            <Briefcase size={16} />
            Kelas Diampu & Input Nilai
          </button>
          <button
            type="button"
            onClick={() => setPortalSubTab('advisees')}
            className={`flex items-center gap-2 px-6 py-3 border-b-2 font-bold text-sm transition-all duration-200 cursor-pointer ${
              portalSubTab === 'advisees'
                ? 'border-monday-blue text-monday-blue bg-monday-blue/5'
                : 'border-transparent text-monday-gray hover:text-monday-black hover:bg-monday-gray-background/20'
            }`}
          >
            <GraduationCap size={16} />
            Mahasiswa Bimbingan Akademik
            {dosenAdviseeStudents?.length > 0 && (
              <span className={`ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold ${
                portalSubTab === 'advisees' ? 'bg-monday-blue text-white' : 'bg-monday-gray-background text-monday-gray'
              }`}>
                {dosenAdviseeStudents.length}
              </span>
            )}
          </button>
        </div>
      )}

      {selectedDosenForPortal ? (
        loadingPortal ? (
          <div className="py-12 text-center text-monday-gray text-sm font-bold animate-pulse">
            Memuat data portal dosen...
          </div>
        ) : portalSubTab === 'classes' ? (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 print:hidden">
            
            {/* Lecturer classes grid list */}
            <div className="lg:col-span-1 space-y-4">
              <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Daftar Kelas Kuliah Diampu</h4>
              {dosenActiveClasses.length > 0 ? (
                <div className="space-y-3">
                  {dosenActiveClasses.map((item) => {
                    const isSelected = selectedClassForGrades?.id === item.id;
                    const mkObj = mataKuliahs.find(mk => mk.id === item.course_id);
                    return (
                      <button
                        key={item.id}
                        onClick={() => selectClassForPortalGrades(item)}
                        className={`w-full text-left p-4 rounded-2xl border transition-300 flex flex-col gap-2 ${
                          isSelected 
                            ? 'bg-monday-blue/10 border-monday-blue/30 text-monday-blue shadow-sm font-bold' 
                            : 'bg-white border-monday-border hover:bg-monday-gray-background/40 text-monday-black font-semibold'
                        }`}
                      >
                        <div className="flex items-center justify-between">
                          <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-monday-background border border-monday-border rounded-md text-monday-gray font-mono">
                            {mkObj?.code || 'MK'}
                          </span>
                          <span className="text-xs font-extrabold">Kelas: {item.class_name}</span>
                        </div>
                        <span className="font-bold text-sm block leading-tight">{mkObj?.name || 'Nama Mata Kuliah'}</span>
                        <div className="flex items-center justify-between mt-1 text-[11px] font-semibold text-monday-gray">
                          <span>SKS: {mkObj?.sks || '-'} SKS</span>
                          <span>Ruangan: {item.room || '-'}</span>
                        </div>
                      </button>
                    );
                  })}
                </div>
              ) : (
                <div className="p-6 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal">
                  Dosen ini belum ditugaskan mengampu kelas apapun pada semester aktif ini.
                </div>
              )}
            </div>

            {/* Student grade input list */}
            <div className="lg:col-span-2 space-y-4">
              <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Lembar Input Nilai Kelas Kuliah</h4>
              {selectedClassForGrades ? (
                <div className="border border-monday-border rounded-2xl overflow-hidden bg-white shadow-sm space-y-4 p-5">
                  <div className="flex items-center justify-between pb-3 border-b border-monday-border">
                    <div>
                      <span className="text-xs text-monday-gray font-bold uppercase block">Mata Kuliah</span>
                      <h5 className="font-extrabold text-base text-monday-black mt-0.5">
                        {mataKuliahs.find(mk => mk.id === selectedClassForGrades.course_id)?.name} (Kelas {selectedClassForGrades.class_name})
                      </h5>
                    </div>
                    <div className="flex items-center gap-2">
                      <button
                        type="button"
                        onClick={() => setShowAttendanceModal(true)}
                        className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-xs transition-300 shadow-sm shadow-emerald-600/10 cursor-pointer"
                      >
                        Lihat Absen Kelas
                      </button>
                      <span className="px-2.5 py-1 bg-monday-gray-background border border-monday-border text-monday-black rounded-lg font-bold text-xs">
                        Ruang: {selectedClassForGrades.room || '-'}
                      </span>
                    </div>
                  </div>

                  <div className="space-y-4">
                    {enrolledStudentsInClass.length > 0 ? (
                      <div className="divide-y divide-monday-border">
                        {enrolledStudentsInClass.map((enroll) => {
                          const studentData = students.find(m => m.id === enroll.student_id);
                          return (
                            <div key={enroll.id} className="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                              <div className="space-y-0.5">
                                <span className="text-xs font-mono text-monday-blue font-bold">{studentData?.nim}</span>
                                <h5 className="font-bold text-sm text-monday-black">{studentData?.name}</h5>
                              </div>
                              
                              <div className="flex items-center gap-3">
                                <div className="space-y-1">
                                  <label className="text-[10px] uppercase font-bold text-monday-gray block">Nilai Angka</label>
                                  <input 
                                    type="number"
                                    min="0"
                                    max="100"
                                    value={updatingGrades[enroll.id]?.final_score !== undefined ? updatingGrades[enroll.id].final_score : ''}
                                    onChange={(e) => {
                                      const val = e.target.value;
                                      let letterGrade = '';
                                      if (val !== '') {
                                        const score = parseFloat(val);
                                        if (!isNaN(score)) {
                                          if (score >= 80 && score <= 100) letterGrade = 'A';
                                          else if (score >= 70 && score < 80) letterGrade = 'B';
                                          else if (score >= 55 && score < 70) letterGrade = 'C';
                                          else if (score >= 40 && score < 55) letterGrade = 'D';
                                          else if (score >= 0 && score < 40) letterGrade = 'E';
                                        }
                                      }
                                      setUpdatingGrades(prev => ({
                                        ...prev,
                                        [enroll.id]: { 
                                          ...prev[enroll.id], 
                                          final_score: val,
                                          letter_grade: letterGrade
                                        }
                                      }));
                                    }}
                                    className="w-20 px-2 py-1 bg-white border border-monday-border rounded-lg text-center text-sm font-semibold focus:outline-none focus:border-monday-blue"
                                    placeholder="0-100"
                                  />
                                </div>

                                <div className="space-y-1">
                                  <label className="text-[10px] uppercase font-bold text-monday-gray block">Huruf</label>
                                  <input 
                                    type="text"
                                    maxLength={2}
                                    value={updatingGrades[enroll.id]?.letter_grade || ''}
                                    disabled
                                    className="w-12 px-2 py-1 bg-monday-gray-background border border-monday-border rounded-lg text-center text-sm font-bold text-monday-gray cursor-not-allowed"
                                    placeholder="-"
                                  />
                                </div>

                                <div className="pt-5">
                                  <button
                                    onClick={() => saveStudentGrade(enroll.id)}
                                    className="px-4 py-1.5 bg-monday-blue text-white rounded-full text-xs font-bold transition-300"
                                  >
                                    Simpan
                                  </button>
                                </div>
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    ) : (
                      <div className="py-10 text-center text-xs text-monday-gray font-semibold">
                        Belum ada mahasiswa terdaftar di kelas kuliah ini.
                      </div>
                    )}
                  </div>
                </div>
              ) : (
                <div className="p-8 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal">
                  <AlertTriangle size={24} className="mx-auto mb-2 text-monday-gray" />
                  Silakan pilih salah satu kelas kuliah di sebelah kiri untuk melihat daftar mahasiswa & mengisi nilai.
                </div>
              )}
            </div>

          </div>
        ) : (
          // Advisees view
          <div className="space-y-4 print:hidden">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="space-y-0.5">
                <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">
                  Daftar Mahasiswa Bimbingan Akademik (Perwalian)
                </h4>
                <p className="text-xs text-monday-gray font-semibold">
                  Berikut adalah daftar mahasiswa yang berada di bawah bimbingan akademik Anda.
                </p>
              </div>
              
              {/* Local Search Bar */}
              <div className="relative w-full md:w-72">
                <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
                <input 
                  type="text" 
                  placeholder="Cari name atau NIM..." 
                  value={adviseeSearchQuery}
                  onChange={(e) => setAdviseeSearchQuery(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 bg-monday-background border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black transition-300"
                />
              </div>
            </div>

            {dosenAdviseeStudents && dosenAdviseeStudents.length > 0 ? (
              (() => {
                const query = adviseeSearchQuery.toLowerCase();
                const filtered = dosenAdviseeStudents.filter(m => 
                  m.name.toLowerCase().includes(query) ||
                  m.nim.toLowerCase().includes(query)
                );
                
                return filtered.length > 0 ? (
                  <div className="border border-monday-border rounded-2xl overflow-hidden bg-white shadow-sm">
                    <table className="w-full text-left border-collapse">
                      <thead>
                        <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
                          <th className="py-4 px-6 w-16 text-center">No</th>
                          <th className="py-4 px-6 w-36">NIM</th>
                          <th className="py-4 px-6">Nama Lengkap</th>
                          <th className="py-4 px-6">Program Studi</th>
                          <th className="py-4 px-6 text-center">Angkatan</th>
                          <th className="py-4 px-6 text-center">Status</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-monday-border text-sm text-monday-black">
                        {filtered.map((m, index) => {
                          const prObj = studyPrograms.find(p => p.id === m.study_program_id);
                          const isAktif = m.status === 'AKTIF';
                          return (
                            <tr key={m.id} className="hover:bg-monday-gray-background/30 transition-colors">
                              <td className="py-3.5 px-6 text-center text-monday-gray font-mono font-semibold">{index + 1}</td>
                              <td className="py-3.5 px-6 font-bold text-monday-blue font-mono">{m.nim}</td>
                              <td className="py-3.5 px-6 font-semibold">{m.name}</td>
                              <td className="py-3.5 px-6">
                                {prObj ? (
                                  <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-xl text-xs font-bold text-monday-gray">
                                    {prObj.name}
                                  </span>
                                ) : '-'}
                              </td>
                              <td className="py-3.5 px-6 text-center font-semibold text-monday-gray">
                                {m.enrollment_year || '-'}
                              </td>
                              <td className="py-3.5 px-6 text-center">
                                <span className={`inline-block px-3 py-1 rounded-full text-xs font-bold ${
                                  isAktif 
                                    ? 'bg-monday-lime-green/20 text-monday-black border border-monday-lime-green/30' 
                                    : 'bg-monday-gray-background text-monday-gray border border-monday-border'
                                }`}>
                                  {m.status || '-'}
                                </span>
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  </div>
                ) : (
                  <div className="py-12 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal">
                    Tidak ada mahasiswa bimbingan yang cocok dengan pencarian "{adviseeSearchQuery}".
                  </div>
                );
              })()
            ) : (
              <div className="py-12 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal">
                Belum ada data mahasiswa bimbingan akademik (perwalian) untuk dosen ini.
              </div>
            )}
          </div>
        )
      ) : (
        <div className="p-8 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal print:hidden">
          <AlertTriangle size={24} className="mx-auto mb-2 text-monday-gray" />
          Hubungkan/simulasikan login Dosen Pengampu terlebih dahulu melalui dropdown di atas.
        </div>
      )}

      {/* Attendance Modal */}
      {showAttendanceModal && selectedClassForGrades && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-monday-black/40 backdrop-blur-sm p-4 animate-fade-in print:static print:p-0 print:bg-white print:backdrop-blur-none">
          <div className="bg-white rounded-3xl p-6 shadow-2xl border border-monday-border max-w-3xl w-full flex flex-col gap-5 max-h-[85vh] print:max-h-none print:shadow-none print:border-none print:rounded-none">
            
            {/* Modal Header */}
            <div className="flex items-center justify-between pb-3 border-b border-monday-border print:hidden">
              <h3 className="font-extrabold text-lg text-monday-black">
                Daftar Hadir & Absen Kelas
              </h3>
              <button
                type="button"
                onClick={() => setShowAttendanceModal(false)}
                className="px-4 py-2 bg-monday-background border border-monday-border text-monday-black hover:bg-monday-gray-background rounded-full font-bold text-xs transition-300"
              >
                Tutup
              </button>
            </div>

            {/* Printable Content Wrapper */}
            <div className="overflow-y-auto flex-1 space-y-4 pr-1 print:overflow-visible">
              
              {/* Header Info */}
              <div className="text-center pb-4 border-b-2 border-double border-monday-border">
                <h2 className="font-extrabold text-xl text-monday-black uppercase">DAFTAR HADIR MAHASISWA (ABSENSI)</h2>
                <h3 className="font-bold text-sm text-monday-gray uppercase tracking-wider mt-1">SIAKAD SUZURAN - UNIVERSITAS SUZURAN</h3>
              </div>

              {/* Class Meta Grid */}
              <div className="grid grid-cols-2 gap-4 text-xs font-semibold text-monday-black bg-monday-background p-4 rounded-2xl border border-monday-border print:bg-transparent print:border-none print:p-0">
                <div className="space-y-1">
                  <p><span className="text-monday-gray">Mata Kuliah:</span> {mataKuliahs.find(mk => mk.id === selectedClassForGrades.course_id)?.name}</p>
                  <p><span className="text-monday-gray">Kode MK:</span> {mataKuliahs.find(mk => mk.id === selectedClassForGrades.course_id)?.code}</p>
                  <p><span className="text-monday-gray">Kelas:</span> {selectedClassForGrades.class_name}</p>
                </div>
                <div className="space-y-1 text-right print:text-left">
                  <p><span className="text-monday-gray">Ruangan:</span> {selectedClassForGrades.room || '-'}</p>
                  <p><span className="text-monday-gray">Dosen Pengampu:</span> {lecturers.find(d => d.id == selectedDosenForPortal)?.name}</p>
                  <p><span className="text-monday-gray">Total Mahasiswa:</span> {enrolledStudentsInClass.length} orang</p>
                </div>
              </div>

              {/* Table */}
              <div className="border border-monday-border rounded-2xl overflow-hidden bg-white mt-4 print:border-collapse print:rounded-none">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray print:bg-gray-100">
                      <th className="py-3 px-4 w-12 text-center">No</th>
                      <th className="py-3 px-4 w-32">NIM</th>
                      <th className="py-3 px-4">Nama Mahasiswa</th>
                      <th className="py-3 px-4 w-48 text-center" colSpan={2}>Tanda Tangan / Kehadiran</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-monday-border text-xs text-monday-black">
                    {enrolledStudentsInClass.map((enroll, idx) => {
                      const studentData = students.find(m => m.id === enroll.student_id);
                      const isOdd = idx % 2 === 0;
                      return (
                        <tr key={enroll.id} className="hover:bg-monday-gray-background/30 transition-colors">
                          <td className="py-3 px-4 text-center font-mono font-semibold">{idx + 1}</td>
                          <td className="py-3 px-4 font-bold text-monday-blue font-mono">{studentData?.nim}</td>
                          <td className="py-3 px-4 font-semibold">{studentData?.name}</td>
                          {isOdd ? (
                            <>
                              <td className="py-3 px-2 w-24 border-r border-monday-border text-left font-mono text-[10px] text-monday-gray">
                                {idx + 1}. ....................
                              </td>
                              <td className="py-3 px-2 w-24 text-center"></td>
                            </>
                          ) : (
                            <>
                              <td className="py-3 px-2 w-24 border-r border-monday-border text-center"></td>
                              <td className="py-3 px-2 w-24 text-left font-mono text-[10px] text-monday-gray">
                                {idx + 1}. ....................
                              </td>
                            </>
                          )}
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Print action footer */}
            <div className="flex justify-end gap-3 pt-3 border-t border-monday-border print:hidden">
              <button
                type="button"
                onClick={() => window.print()}
                className="px-5 py-2.5 bg-monday-blue text-white hover:bg-opacity-90 rounded-full font-bold text-xs flex items-center gap-2 shadow-md shadow-monday-blue/10 cursor-pointer"
              >
                Cetak Absen (PDF)
              </button>
            </div>

          </div>
        </div>
      )}
    </div>
  );
}
