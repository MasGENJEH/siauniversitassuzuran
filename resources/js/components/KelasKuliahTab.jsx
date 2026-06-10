import React, { useState, useEffect } from 'react';
import { Layers, Plus, Search, Edit, Trash2, ChevronDown, Eye } from 'lucide-react';

export default function KelasKuliahTab({
  kelasKuliahs,
  mataKuliahs,
  tahunAkademiks,
  dosenPengampus,
  lecturers,
  kelasMahasiswas = [],
  students = [],
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [visibleCount, setVisibleCount] = useState(10);
  const [selectedClassForDetail, setSelectedClassForDetail] = useState(null);

  // Reset limit to 10 when searching
  useEffect(() => {
    setVisibleCount(10);
  }, [searchQuery]);

  const filteredItems = kelasKuliahs.filter(k => {
    const mk = mataKuliahs.find(m => m.id === k.course_id);
    const mkName = mk ? mk.name.toLowerCase() : '';
    return k.class_name.toLowerCase().includes(searchQuery.toLowerCase()) || mkName.includes(searchQuery.toLowerCase());
  });

  const itemsToDisplay = filteredItems.slice(0, visibleCount);

  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <Layers className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Kelas Kuliah & Team Teaching
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola kelas perkuliahan aktif, penugasan dosen tunggal, atau team teaching pengampu. Total: {kelasKuliahs.length} kelas terdaftar.
          </p>
        </div>
        <button
          onClick={() => openModal('kelasKuliah', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Kelas Kuliah <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input
            type="text"
            placeholder="Cari kelas..."
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
              <th className="py-4 px-6">Mata Kuliah</th>
              <th className="py-4 px-6">Nama Kelas</th>
              <th className="py-4 px-6">Tahun Akademik</th>
              <th className="py-4 px-6">Jadwal & Ruangan</th>
              <th className="py-4 px-6">Dosen Pengampu</th>
              <th className="py-4 px-6">Peserta</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {itemsToDisplay.map((k, index) => {
              const mkObj = mataKuliahs.find(m => m.id === k.course_id);
              const taObj = tahunAkademiks.find(t => t.id === k.academic_year_id);

              // Get assigned lecturers for this class
              const activeLecturerLinks = dosenPengampus.filter(dp => dp.course_class_id === k.id);
              const linkedDosenNames = activeLecturerLinks.map(dp => {
                const d = lecturers.find(ds => ds.id === dp.lecturer_id);
                return d ? d.name : null;
              }).filter(Boolean);

              // Get student count for this class
              const studentCount = kelasMahasiswas.filter(km => km.course_class_id === k.id).length;

              return (
                <tr key={k.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                  <td className="py-3.5 px-6">
                    {mkObj ? (
                      <div>
                        <span className="font-bold text-monday-blue">{mkObj.code}</span>
                        <span className="ml-2 font-semibold">{mkObj.name}</span>
                      </div>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 font-bold">{k.class_name}</td>
                  <td className="py-3.5 px-6">
                    {taObj ? (
                      <span className="px-2 py-0.5 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                        {taObj.code}
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6">
                    <div className="text-xs text-monday-gray font-semibold">
                      <span className="font-bold text-monday-black block">{k.day}</span>
                      <span>{k.start_time.substring(0, 5)} - {k.end_time.substring(0, 5)} ({k.room})</span>
                    </div>
                  </td>
                  <td className="py-3.5 px-6">
                    <div className="flex flex-wrap gap-1.5 items-center">
                      {linkedDosenNames.length > 0 ? (
                        linkedDosenNames.map((name, i) => (
                          <span key={i} className="px-2 py-0.5 bg-violet-500/10 border border-violet-500/20 text-violet-600 rounded-lg text-[11px] font-bold">
                            {name}
                          </span>
                        ))
                      ) : (
                        <span className="text-monday-red text-xs font-bold italic">Belum Ada Pengampu</span>
                      )}
                    </div>
                  </td>
                  <td className="py-3.5 px-6">
                    <span className="px-2.5 py-1 bg-monday-blue/10 rounded-full text-xs font-bold text-monday-blue font-mono">
                      {studentCount}
                    </span>
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button
                        onClick={() => setSelectedClassForDetail(k)}
                        title="Lihat Detail & Mahasiswa"
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Eye size={16} />
                      </button>
                      <button
                        onClick={() => openModal('kelasKuliah', 'edit', k)}
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Edit size={16} />
                      </button>
                      <button
                        onClick={() => handleDeleteItem('kelasKuliah', k.id)}
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

      {/* Load More Button */}
      {visibleCount < filteredItems.length && (
        <div className="flex justify-center mt-2">
          <button
            type="button"
            onClick={() => setVisibleCount(prev => prev + 10)}
            className="px-6 py-2 bg-monday-blue/10 text-monday-blue hover:bg-monday-blue hover:text-white rounded-full font-bold text-xs transition-all duration-300 flex items-center gap-2"
          >
            Tampilkan Lebih Banyak <ChevronDown size={14} />
          </button>
        </div>
      )}
      {/* Detail Modal */}
      {selectedClassForDetail && (() => {
        const mkObj = mataKuliahs.find(m => m.id === selectedClassForDetail.course_id);
        const taObj = tahunAkademiks.find(t => t.id === selectedClassForDetail.academic_year_id);

        // Dosen Pengampu
        const activeLecturerLinks = dosenPengampus.filter(dp => dp.course_class_id === selectedClassForDetail.id);
        const linkedDosenNames = activeLecturerLinks.map(dp => {
          const d = lecturers.find(ds => ds.id === dp.lecturer_id);
          return d ? d.name : null;
        }).filter(Boolean);

        // Enrolled Students
        const enrollments = kelasMahasiswas.filter(km => km.course_class_id === selectedClassForDetail.id);

        return (
          <div className="fixed inset-0 z-[100] flex items-center justify-center bg-monday-black/40 backdrop-blur-sm p-4 animate-fade-in">
            <div className="bg-white rounded-3xl p-6 shadow-2xl border border-monday-border max-w-3xl w-full flex flex-col gap-6 transform scale-100 transition-all duration-300 max-h-[85vh]">

              {/* Header */}
              <div className="flex items-center justify-between pb-4 border-b border-monday-border">
                <div className="flex flex-col gap-1">
                  <h3 className="font-extrabold text-xl text-monday-black">
                    Detail Kelas & Daftar Mahasiswa
                  </h3>
                  <p className="text-sm font-semibold text-monday-gray">
                    Informasi kelas kuliah dan list mahasiswa yang mengambil kelas ini.
                  </p>
                </div>
                <button
                  onClick={() => setSelectedClassForDetail(null)}
                  className="px-4 py-2 bg-monday-background border border-monday-border hover:bg-monday-gray-background text-monday-black rounded-full font-bold text-xs transition-300"
                >
                  Tutup
                </button>
              </div>

              {/* Class Info grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 bg-monday-background p-5 rounded-2xl border border-monday-border text-sm">
                <div className="flex flex-col gap-2">
                  <p className="font-semibold text-monday-gray">
                    Mata Kuliah: <span className="text-monday-black font-extrabold block mt-0.5">{mkObj ? `${mkObj.code} - ${mkObj.name}` : '-'}</span>
                  </p>
                  <p className="font-semibold text-monday-gray">
                    Nama Kelas: <span className="text-monday-black font-extrabold block mt-0.5">{selectedClassForDetail.class_name}</span>
                  </p>
                  <p className="font-semibold text-monday-gray">
                    Tahun Akademik: <span className="text-monday-black font-extrabold block mt-0.5">{taObj ? taObj.name : '-'}</span>
                  </p>
                </div>
                <div className="flex flex-col gap-2">
                  <p className="font-semibold text-monday-gray">
                    Jadwal Kuliah: <span className="text-monday-black font-extrabold block mt-0.5">{selectedClassForDetail.day}, {selectedClassForDetail.start_time.substring(0, 5)} - {selectedClassForDetail.end_time.substring(0, 5)}</span>
                  </p>
                  <p className="font-semibold text-monday-gray">
                    Ruangan: <span className="text-monday-black font-extrabold block mt-0.5">{selectedClassForDetail.room}</span>
                  </p>
                  <p className="font-semibold text-monday-gray">
                    Dosen Pengampu: <span className="text-monday-black font-extrabold block mt-0.5">{linkedDosenNames.length > 0 ? linkedDosenNames.join(', ') : 'Belum Ada Dosen'}</span>
                  </p>
                </div>
              </div>

              {/* Enrolled Students Table */}
              <div className="flex flex-col gap-3 flex-1 overflow-y-auto min-h-0">
                <h4 className="font-extrabold text-sm text-monday-black uppercase tracking-wider">
                  Mahasiswa Terdaftar ({enrollments.length})
                </h4>

                <div className="border border-monday-border rounded-xl overflow-hidden bg-white max-h-[300px] overflow-y-auto">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray sticky top-0 z-10">
                        <th className="py-3 px-5">No</th>
                        <th className="py-3 px-5">NIM</th>
                        <th className="py-3 px-5">Nama Mahasiswa</th>
                        <th className="py-3 px-5 text-right">Nilai Akhir</th>
                        <th className="py-3 px-5 text-right">Nilai Huruf</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-monday-border text-sm text-monday-black">
                      {enrollments.length > 0 ? (
                        enrollments.map((en, index) => {
                          const mhs = students.find(m => m.id === en.student_id);
                          return (
                            <tr key={en.id} className="hover:bg-monday-gray-background/30 transition-colors">
                              <td className="py-2.5 px-5 text-monday-gray font-mono font-semibold">{index + 1}</td>
                              <td className="py-2.5 px-5 font-bold text-monday-blue font-mono">{mhs ? mhs.nim : '-'}</td>
                              <td className="py-2.5 px-5 font-semibold">{mhs ? mhs.name : 'Tidak Diketahui'}</td>
                              <td className="py-2.5 px-5 text-right font-bold text-monday-black">{en.final_score || '0'}</td>
                              <td className="py-2.5 px-5 text-right">
                                {en.letter_grade ? (
                                  <span className={`inline-block px-2.5 py-1 text-xs font-bold rounded-lg border ${en.letter_grade === 'A' ? 'bg-emerald-500/15 text-emerald-700 border-emerald-500/20' :
                                    en.letter_grade === 'B' ? 'bg-monday-blue/15 text-monday-blue border-monday-blue/20' :
                                      en.letter_grade === 'C' ? 'bg-amber-500/15 text-amber-700 border-amber-500/20' :
                                        en.letter_grade === 'D' ? 'bg-monday-red/15 text-monday-red border-monday-red/20' :
                                          en.letter_grade === 'E' ? 'bg-monday-red/15 text-monday-red border-monday-red/20' :
                                            'bg-monday-background text-monday-gray border-monday-border'
                                    }`}>
                                    {en.letter_grade}
                                  </span>
                                ) : (
                                  <span className="text-monday-gray text-xs italic">-</span>
                                )}
                              </td>
                            </tr>
                          );
                        })
                      ) : (
                        <tr>
                          <td colSpan={5} className="py-8 text-center text-monday-gray font-bold italic">
                            Belum ada mahasiswa yang terdaftar di kelas ini.
                          </td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        );
      })()}

    </div>
  );
}
