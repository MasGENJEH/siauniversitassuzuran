import React from 'react';
import { Calendar, Plus, Search, Edit, Trash2, Check } from 'lucide-react';

export default function TahunAkademikTab({
  tahunAkademiks,
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem,
  toggleTahunAkademikStatus
}) {
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <Calendar className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Tahun Akademik
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data tahun akademik dan aktifkan salah satu semester berjalan.
          </p>
        </div>
        <button 
          onClick={() => openModal('tahunAkademik', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Tahun Akademik <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input 
            type="text" 
            placeholder="Cari tahun akademik..." 
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
              <th className="py-4 px-6">ID</th>
              <th className="py-4 px-6">Kode TA</th>
              <th className="py-4 px-6">Nama TA / Semester</th>
              <th className="py-4 px-6 text-center">Status Keaktifan</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {tahunAkademiks.filter(ta => 
              ta.nama_ta.toLowerCase().includes(searchQuery.toLowerCase()) ||
              ta.kode_ta.toLowerCase().includes(searchQuery.toLowerCase())
            ).map((ta) => (
              <tr key={ta.id} className="hover:bg-monday-gray-background/30 transition-colors">
                <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{ta.id}</td>
                <td className="py-3.5 px-6 font-bold text-monday-blue">{ta.kode_ta}</td>
                <td className="py-3.5 px-6 font-semibold">{ta.nama_ta}</td>
                <td className="py-3.5 px-6 text-center">
                  {ta.status ? (
                    <span className="px-3 py-1 bg-monday-lime-green/20 border border-monday-lime-green/30 text-monday-black text-xs font-bold rounded-full">
                      Aktif
                    </span>
                  ) : (
                    <button 
                      onClick={() => toggleTahunAkademikStatus(ta)}
                      className="px-3 py-1 bg-monday-gray-background border border-monday-border text-monday-gray hover:text-monday-blue hover:border-monday-blue/30 text-xs font-bold rounded-full transition-300 flex items-center gap-1 mx-auto"
                    >
                      <Check size={12} /> Aktifkan
                    </button>
                  )}
                </td>
                <td className="py-3.5 px-6 text-right">
                  <div className="flex items-center justify-end gap-2">
                    <button 
                      onClick={() => openModal('tahunAkademik', 'edit', ta)}
                      className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                    >
                      <Edit size={16} />
                    </button>
                    <button 
                      onClick={() => handleDeleteItem('tahunAkademik', ta.id)}
                      className="p-1.5 text-monday-gray hover:text-monday-red hover:bg-monday-red/10 rounded-xl transition-300"
                    >
                      <Trash2 size={16} />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
