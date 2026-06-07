import React from 'react';
import { Layers, Plus, Search, Edit, Trash2 } from 'lucide-react';

export default function KelasKuliahTab({
  kelasKuliahs,
  mataKuliahs,
  tahunAkademiks,
  dosenPengampus,
  dosens,
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
              <th className="py-4 px-6">ID</th>
              <th className="py-4 px-6">Mata Kuliah</th>
              <th className="py-4 px-6">Nama Kelas</th>
              <th className="py-4 px-6">Tahun Akademik</th>
              <th className="py-4 px-6">Dosen Pengampu</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {kelasKuliahs.filter(k => {
              const mk = mataKuliahs.find(m => m.id === k.id_mk);
              const mkName = mk ? mk.nama_mk.toLowerCase() : '';
              return k.nama_kelas.toLowerCase().includes(searchQuery.toLowerCase()) || mkName.includes(searchQuery.toLowerCase());
            }).map((k) => {
              const mkObj = mataKuliahs.find(m => m.id === k.id_mk);
              const taObj = tahunAkademiks.find(t => t.id === k.id_ta);
              
              // Get assigned lecturers for this class
              const activeLecturerLinks = dosenPengampus.filter(dp => dp.id_kelas === k.id);
              const linkedDosenNames = activeLecturerLinks.map(dp => {
                const d = dosens.find(ds => ds.id === dp.id_dosen);
                return d ? d.nama : null;
              }).filter(Boolean);

              return (
                <tr key={k.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{k.id}</td>
                  <td className="py-3.5 px-6">
                    {mkObj ? (
                      <div>
                        <span className="font-bold text-monday-blue">{mkObj.kode_mk}</span>
                        <span className="ml-2 font-semibold">{mkObj.nama_mk}</span>
                      </div>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 font-bold">{k.nama_kelas}</td>
                  <td className="py-3.5 px-6">
                    {taObj ? (
                      <span className="px-2 py-0.5 bg-monday-background border border-monday-border rounded-lg text-xs font-bold text-monday-gray">
                        {taObj.kode_ta}
                      </span>
                    ) : '-'}
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
                      
                      <button 
                        onClick={() => openModal('dosenPengampu', 'assign', k)}
                        className="px-2 py-0.5 bg-monday-blue text-white rounded-lg text-[10px] font-bold hover:bg-opacity-90 transition-300 ml-1.5"
                      >
                        + Atur Dosen
                      </button>
                    </div>
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
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
    </div>
  );
}
