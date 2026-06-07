import React from 'react';
import { Edit3, Plus, Search, Edit, Trash2 } from 'lucide-react';

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
              <th className="py-4 px-6">ID</th>
              <th className="py-4 px-6">Mahasiswa</th>
              <th className="py-4 px-6">Mata Kuliah / Kelas</th>
              <th className="py-4 px-6 text-center">Nilai Angka</th>
              <th className="py-4 px-6 text-center">Nilai Huruf</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {kelasMahasiswas.filter(km => {
              const m = mahasiswas.find(std => std.id === km.id_mahasiswa);
              return m ? m.nama.toLowerCase().includes(searchQuery.toLowerCase()) : false;
            }).map((km) => {
              const stdObj = mahasiswas.find(m => m.id === km.id_mahasiswa);
              const kkObj = kelasKuliahs.find(k => k.id === km.id_kelas);
              const mkObj = kkObj ? mataKuliahs.find(m => m.id === kkObj.id_mk) : null;
              
              return (
                <tr key={km.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{km.id}</td>
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
                    {km.nilai_angka !== null ? km.nilai_angka : <span className="text-monday-gray font-normal italic">Belum Dinilai</span>}
                  </td>
                  <td className="py-3.5 px-6 text-center">
                    {km.nilai_huruf ? (
                      <span className={`px-2.5 py-1 text-xs font-bold rounded-lg ${
                        ['A', 'B'].includes(km.nilai_huruf) ? 'bg-monday-lime-green/20 text-monday-black' :
                        ['C', 'D'].includes(km.nilai_huruf) ? 'bg-amber-500/10 text-amber-600' :
                        km.nilai_huruf === 'E' ? 'bg-monday-red/10 text-monday-red' : 'bg-monday-background text-monday-gray'
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
    </div>
  );
}
