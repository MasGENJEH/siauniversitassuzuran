import React from 'react';
import { Award, Plus, Search, Edit, Trash2 } from 'lucide-react';
import PageHeader from './ui/PageHeader';
import SearchInput from './ui/SearchInput';
import ActionButtons from './ui/ActionButtons';

export default function ProdiTab({
  studyPrograms,
  faculties,
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <PageHeader 
        title="Manage Program Studi"
        description={`Kelola data program studi dan hubungannya dengan faculties. Total: ${studyPrograms.length} program studi terdaftar.`}
        icon={Award}
        actionLabel="Tambah Program Studi"
        actionIcon={Plus}
        onActionClick={() => openModal('prodi', 'create')}
      />

      <div className="flex items-center justify-between">
        <SearchInput 
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          placeholder="Cari program studi..."
        />
      </div>

      <div className="border border-monday-border rounded-2xl overflow-x-auto overflow-y-hidden bg-white">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-monday-gray-background border-b border-monday-border text-xs font-bold uppercase tracking-wider text-monday-gray">
              <th className="py-4 px-6">No</th>
              <th className="py-4 px-6">Kode Prodi</th>
              <th className="py-4 px-6">Nama Program Studi</th>
              <th className="py-4 px-6">Fakultas</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {studyPrograms.filter(p => 
              p.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
              p.code.toLowerCase().includes(searchQuery.toLowerCase())
            ).map((pr, index) => {
              const fakObj = faculties.find(f => f.id === pr.faculty_id);
              return (
                <tr key={pr.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-blue">{pr.code}</td>
                  <td className="py-3.5 px-6 font-semibold">{pr.name}</td>
                  <td className="py-3.5 px-6">
                    {fakObj ? (
                      <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-xl text-xs font-bold text-monday-gray">
                        {fakObj.name}
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <ActionButtons 
                      onEdit={() => openModal('prodi', 'edit', pr)}
                      onDelete={() => handleDeleteItem('prodi', pr.id)}
                    />
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
