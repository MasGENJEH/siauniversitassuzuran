import React, { useState, useEffect } from 'react';
import { Edit3, Plus, Search, Edit, Trash2, ChevronDown } from 'lucide-react';

export default function KelasMahasiswaTab({
  kelasMahasiswas,
  mahasiswas,
  kelasKuliahs,
  mataKuliahs,
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

  const filteredItems = kelasMahasiswas.filter(km => {
    const m = mahasiswas.find(std => std.id === km.id_mahasiswa);
    return m ? m.nama.toLowerCase().includes(searchQuery.toLowerCase()) : false;
  });

  const itemsToDisplay = filteredItems.slice(0, visibleCount);
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
            Daftarkan mahasiswa ke kelas kuliah (Rencana Studi / KRS) dan pantau hasil akhir studi (Hasil Studi / KHS). Total: {kelasMahasiswas.length} data.
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
            placeholder="Cari nama mahasiswa..." 
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
              <th className="py-4 px-6">Mata Kuliah / Kelas</th>
              <th className="py-4 px-6 text-center">Nilai Angka</th>
              <th className="py-4 px-6 text-center">Nilai Huruf</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {itemsToDisplay.map((km, index) => {
              const stdObj = mahasiswas.find(m => m.id === km.id_mahasiswa);
              const kkObj = kelasKuliahs.find(k => k.id === km.id_kelas);
              const mkObj = kkObj ? mataKuliahs.find(m => m.id === kkObj.id_mk) : null;
              
              return (
                <tr key={km.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                  <td className="py-3.5 px-6">
                    {stdObj ? (
                      <div>
                        <span className="font-bold text-monday-blue">{stdObj.nim}</span>
                        <span className="ml-2 font-semibold">{stdObj.nama}</span>
                      </div>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6">
                    {mkObj ? (
                      <div>
                        <span className="font-semibold text-monday-black">{mkObj.nama_mk}</span>
                        <span className="ml-2 px-1.5 py-0.5 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                          Kelas: {kkObj.nama_kelas}
                        </span>
                      </div>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-center font-bold">
                    {km.nilai_akhir !== null && km.nilai_akhir !== undefined ? km.nilai_akhir : <span className="text-monday-gray font-normal italic">Belum Dinilai</span>}
                  </td>
                  <td className="py-3.5 px-6 text-center">
                    {km.nilai_huruf ? (
                      <span className={`px-2.5 py-1 text-xs font-bold rounded-lg border ${
                        km.nilai_huruf === 'A' ? 'bg-emerald-500/15 text-emerald-700 border-emerald-500/20' :
                        km.nilai_huruf === 'B' ? 'bg-monday-blue/15 text-monday-blue border-monday-blue/20' :
                        km.nilai_huruf === 'C' ? 'bg-amber-500/15 text-amber-700 border-amber-500/20' :
                        km.nilai_huruf === 'D' ? 'bg-monday-gray/15 text-monday-gray border-monday-gray/20' :
                        km.nilai_huruf === 'E' ? 'bg-monday-red/15 text-monday-red border-monday-red/20' :
                        'bg-monday-background text-monday-gray border-monday-border'
                      }`}>
                        {km.nilai_huruf}
                      </span>
                    ) : (
                      <span className="text-monday-gray text-xs italic">N/A</span>
                    )}
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
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
