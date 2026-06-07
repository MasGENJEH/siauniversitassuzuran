import React from 'react';
import { Briefcase, AlertTriangle, Search, Info } from 'lucide-react';

export default function LecturerPortalTab({
  dosens,
  mataKuliahs,
  mahasiswas,
  kelasKuliahs,
  kelasMahasiswas,
  dosenActiveClasses,
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
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
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

      {/* Select active lecturer */}
      <div className="p-5 rounded-2xl bg-monday-background border border-monday-border flex flex-col md:flex-row items-center gap-4">
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
            {dosens.map(d => (
              <option key={d.id} value={d.id}>{d.nama} (NIDN: {d.nidn})</option>
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

      {selectedDosenForPortal ? (
        loadingPortal ? (
          <div className="py-12 text-center text-monday-gray text-sm font-bold animate-pulse">
            Memuat data kelas diampu dosen...
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {/* Lecturer classes grid list */}
            <div className="lg:col-span-1 space-y-4">
              <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Daftar Kelas Kuliah Diampu</h4>
              {dosenActiveClasses.length > 0 ? (
                <div className="space-y-3">
                  {dosenActiveClasses.map((item) => {
                    const isSelected = selectedClassForGrades?.id === item.id;
                    const mkObj = mataKuliahs.find(mk => mk.id === item.id_mk);
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
                            {mkObj?.kode_mk || 'MK'}
                          </span>
                          <span className="text-xs font-extrabold">Kelas: {item.nama_kelas}</span>
                        </div>
                        <span className="font-bold text-sm block leading-tight">{mkObj?.nama_mk || 'Nama Mata Kuliah'}</span>
                        <div className="flex items-center justify-between mt-1 text-[11px] font-semibold text-monday-gray">
                          <span>SKS: {mkObj?.sks || '-'} SKS</span>
                          <span>Ruangan: {item.ruangan || '-'}</span>
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
                        {mataKuliahs.find(mk => mk.id === selectedClassForGrades.id_mk)?.nama_mk} (Kelas {selectedClassForGrades.nama_kelas})
                      </h5>
                    </div>
                    <span className="px-2.5 py-1 bg-monday-gray-background border border-monday-border text-monday-black rounded-lg font-bold text-xs">
                      Ruang: {selectedClassForGrades.ruangan || '-'}
                    </span>
                  </div>

                  <div className="space-y-4">
                    {enrolledStudentsInClass.length > 0 ? (
                      <div className="divide-y divide-monday-border">
                        {enrolledStudentsInClass.map((enroll) => {
                          const studentData = mahasiswas.find(m => m.id === enroll.id_mahasiswa);
                          return (
                            <div key={enroll.id} className="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                              <div className="space-y-0.5">
                                <span className="text-xs font-mono text-monday-blue font-bold">{studentData?.nim}</span>
                                <h5 className="font-bold text-sm text-monday-black">{studentData?.nama}</h5>
                              </div>
                              
                              <div className="flex items-center gap-3">
                                <div className="space-y-1">
                                  <label className="text-[10px] uppercase font-bold text-monday-gray block">Nilai Angka</label>
                                  <input 
                                    type="number"
                                    min="0"
                                    max="100"
                                    value={updatingGrades[enroll.id]?.nilai_akhir !== undefined ? updatingGrades[enroll.id].nilai_akhir : ''}
                                    onChange={(e) => {
                                      const val = e.target.value;
                                      setUpdatingGrades(prev => ({
                                        ...prev,
                                        [enroll.id]: { ...prev[enroll.id], nilai_akhir: val }
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
                                    value={updatingGrades[enroll.id]?.nilai_huruf || ''}
                                    onChange={(e) => {
                                      const val = e.target.value.toUpperCase();
                                      setUpdatingGrades(prev => ({
                                        ...prev,
                                        [enroll.id]: { ...prev[enroll.id], nilai_huruf: val }
                                      }));
                                    }}
                                    className="w-12 px-2 py-1 bg-white border border-monday-border rounded-lg text-center text-sm font-bold focus:outline-none focus:border-monday-blue"
                                    placeholder="A/B"
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
        )
      ) : (
        <div className="p-8 bg-monday-background rounded-2xl border border-dashed border-monday-border text-center text-monday-gray text-xs font-bold leading-normal">
          <AlertTriangle size={24} className="mx-auto mb-2 text-monday-gray" />
          Hubungkan/simulasikan login Dosen Pengampu terlebih dahulu melalui dropdown di atas.
        </div>
      )}
    </div>
  );
}
