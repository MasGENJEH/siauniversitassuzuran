import React, { useState, useEffect } from 'react';
import { BookOpen, Plus, Search, Edit, Trash2, ChevronDown } from 'lucide-react';

export default function MataKuliahTab({
  mataKuliahs,
  prodis,
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [visibleCount, setVisibleCount] = useState(10);

  // Reset limit to 10 when searching
  useEffect(() => {
    setVisibleCount(10);
  }, [searchQuery]);

  const filteredItems = mataKuliahs.filter(mk => 
    mk.nama_mk.toLowerCase().includes(searchQuery.toLowerCase()) ||
    mk.kode_mk.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const itemsToDisplay = filteredItems.slice(0, visibleCount);

  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <BookOpen className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Mata Kuliah
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data kurikulum mata kuliah, jumlah SKS, dan relasi program studi. Total: {mataKuliahs.length} mata kuliah terdaftar.
          </p>
        </div>
        <button 
          onClick={() => openModal('mataKuliah', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Mata Kuliah <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input 
            type="text" 
            placeholder="Cari mata kuliah..." 
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
              <th className="py-4 px-6">Kode MK</th>
              <th className="py-4 px-6">Nama Mata Kuliah</th>
              <th className="py-4 px-6">SKS</th>
              <th className="py-4 px-6">Program Studi</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {itemsToDisplay.map((mk, index) => {
              const prObj = prodis.find(p => p.id === mk.id_prodi);
              return (
                <tr key={mk.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-blue">{mk.kode_mk}</td>
                  <td className="py-3.5 px-6 font-semibold">{mk.nama_mk}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-black">{mk.sks} SKS</td>
                  <td className="py-3.5 px-6">
                    {prObj ? (
                      <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-xl text-xs font-bold text-monday-gray">
                        {prObj.nama_prodi}
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button 
                        onClick={() => openModal('mataKuliah', 'edit', mk)}
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Edit size={16} />
                      </button>
                      <button 
                        onClick={() => handleDeleteItem('mataKuliah', mk.id)}
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
    </div>
  );
}
