import React from 'react';
import { Building, Plus, Search, Edit, Trash2 } from 'lucide-react';

export default function FakultasTab({
  faculties,
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
            <Building className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Fakultas
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data faculties universitas. Total: {faculties.length} faculties terdaftar.
          </p>
        </div>
        <button 
          onClick={() => openModal('faculties', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Fakultas <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input 
            type="text" 
            placeholder="Cari faculties..." 
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
              <th className="py-4 px-6">Kode Fakultas</th>
              <th className="py-4 px-6">Nama Fakultas</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {faculties.filter(f => 
              f.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
              f.code.toLowerCase().includes(searchQuery.toLowerCase())
            ).map((fak, index) => (
              <tr key={fak.id} className="hover:bg-monday-gray-background/30 transition-colors">
                <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                <td className="py-3.5 px-6 font-bold text-monday-blue">{fak.code}</td>
                <td className="py-3.5 px-6 font-semibold">{fak.name}</td>
                <td className="py-3.5 px-6 text-right">
                  <div className="flex items-center justify-end gap-2">
                    <button 
                      onClick={() => openModal('faculties', 'edit', fak)}
                      className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                    >
                      <Edit size={16} />
                    </button>
                    <button 
                      onClick={() => handleDeleteItem('faculties', fak.id)}
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
