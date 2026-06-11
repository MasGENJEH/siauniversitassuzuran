import React from 'react';
import { Building, Plus, Search, Edit, Trash2 } from 'lucide-react';
import PageHeader from './ui/PageHeader';
import SearchInput from './ui/SearchInput';
import ActionButtons from './ui/ActionButtons';

export default function FakultasTab({
  faculties,
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <PageHeader 
        title="Manage Fakultas"
        description={`Kelola data faculties universitas. Total: ${faculties.length} faculties terdaftar.`}
        icon={Building}
        actionLabel="Tambah Fakultas"
        actionIcon={Plus}
        onActionClick={() => openModal('faculties', 'create')}
      />

      <div className="flex items-center justify-between">
        <SearchInput 
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          placeholder="Cari faculties..."
        />
      </div>

      <div className="border border-monday-border rounded-2xl overflow-x-auto overflow-y-hidden bg-white">
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
                  <ActionButtons 
                    onEdit={() => openModal('faculties', 'edit', fak)}
                    onDelete={() => handleDeleteItem('faculties', fak.id)}
                  />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
