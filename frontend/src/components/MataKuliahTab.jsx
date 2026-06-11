import React, { useState, useEffect } from 'react';
import { BookOpen, Plus, Search, Edit, Trash2, ChevronDown } from 'lucide-react';
import PageHeader from './ui/PageHeader';
import SearchInput from './ui/SearchInput';
import ActionButtons from './ui/ActionButtons';

export default function MataKuliahTab({
  mataKuliahs,
  studyPrograms,
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
    mk.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    mk.code.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const itemsToDisplay = filteredItems.slice(0, visibleCount);

  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <PageHeader 
        title="Manage Mata Kuliah"
        description={`Kelola data kurikulum mata kuliah, jumlah SKS, dan relasi program studi. Total: ${mataKuliahs.length} mata kuliah terdaftar.`}
        icon={BookOpen}
        actionLabel="Tambah Mata Kuliah"
        actionIcon={Plus}
        onActionClick={() => openModal('mataKuliah', 'create')}
      />

      <div className="flex items-center justify-between">
        <SearchInput 
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          placeholder="Cari mata kuliah..."
        />
      </div>

      <div className="border border-monday-border rounded-2xl overflow-x-auto overflow-y-hidden bg-white">
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
              const prObj = studyPrograms.find(p => p.id === mk.study_program_id);
              return (
                <tr key={mk.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{index + 1}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-blue">{mk.code}</td>
                  <td className="py-3.5 px-6 font-semibold">{mk.name}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-black">{mk.sks} SKS</td>
                  <td className="py-3.5 px-6">
                    {prObj ? (
                      <span className="px-2.5 py-1 bg-monday-background border border-monday-border rounded-xl text-xs font-bold text-monday-gray">
                        {prObj.name}
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <ActionButtons 
                      onEdit={() => openModal('mataKuliah', 'edit', mk)}
                      onDelete={() => handleDeleteItem('mataKuliah', mk.id)}
                    />
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
