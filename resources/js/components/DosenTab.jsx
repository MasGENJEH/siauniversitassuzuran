import React, { useState, useEffect } from 'react';
import { Users, Plus, Search, Edit, Trash2, ChevronLeft, ChevronRight } from 'lucide-react';

export default function DosenTab({
  dosens,
  users,
  searchQuery,
  setSearchQuery,
  openModal,
  handleDeleteItem
}) {
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 10;

  // Reset to page 1 when search query changes
  useEffect(() => {
    setCurrentPage(1);
  }, [searchQuery]);

  // Filter items based on search query
  const filteredItems = dosens.filter(d => 
    d.nama.toLowerCase().includes(searchQuery.toLowerCase()) ||
    d.nidn.toLowerCase().includes(searchQuery.toLowerCase())
  );

  // Pagination bounds & slice
  const totalItems = filteredItems.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
  const paginatedItems = filteredItems.slice(startIndex, endIndex);

  return (
    <div className="flex flex-col gap-6 flex-1 rounded-3xl p-6 bg-white border border-monday-border shadow-sm">
      <div className="flex items-center justify-between pb-4 border-b border-monday-border">
        <div className="flex flex-col gap-[4px]">
          <p className="flex items-center gap-[6px]">
            <Users className="size-6 text-monday-black" />
            <span className="font-extrabold text-2xl text-monday-black">
              Manage Data Dosen
            </span>
          </p>
          <p className="font-semibold text-sm text-monday-gray">
            Kelola data staf pengajar/dosen dan kredensial akun pengguna mereka. Total: {dosens.length} dosen terdaftar.
          </p>
        </div>
        <button 
          onClick={() => openModal('dosen', 'create')}
          className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300 flex items-center gap-2"
        >
          Tambah Dosen <Plus size={16} />
        </button>
      </div>

      <div className="flex items-center justify-between">
        <div className="relative w-72">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-monday-gray" size={16} />
          <input 
            type="text" 
            placeholder="Cari dosen..." 
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
              <th className="py-4 px-6">NIDN</th>
              <th className="py-4 px-6">Nama Lengkap</th>
              <th className="py-4 px-6">Akun User</th>
              <th className="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-monday-border text-sm text-monday-black">
            {paginatedItems.map((d, index) => {
              const uObj = users.find(u => u.id === d.id_user);
              return (
                <tr key={d.id} className="hover:bg-monday-gray-background/30 transition-colors">
                  <td className="py-3.5 px-6 text-monday-gray font-mono font-semibold">{startIndex + index + 1}</td>
                  <td className="py-3.5 px-6 font-bold text-monday-blue">{d.nidn}</td>
                  <td className="py-3.5 px-6 font-semibold">{d.nama}</td>
                  <td className="py-3.5 px-6">
                    {uObj ? (
                      <span className="px-2.5 py-1 bg-violet-500/10 border border-violet-500/20 rounded-xl text-xs font-bold text-violet-600 font-mono">
                        {uObj.username} ({uObj.email})
                      </span>
                    ) : '-'}
                  </td>
                  <td className="py-3.5 px-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button 
                        onClick={() => openModal('dosen', 'edit', d)}
                        className="p-1.5 text-monday-gray hover:text-monday-blue hover:bg-monday-blue/10 rounded-xl transition-300"
                      >
                        <Edit size={16} />
                      </button>
                      <button 
                        onClick={() => handleDeleteItem('dosen', d.id)}
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

      {/* Pagination Controls */}
      {totalPages > 1 && (
        <div className="flex items-center justify-between pt-4 border-t border-monday-border mt-2">
          <p className="text-sm font-semibold text-monday-gray">
            Menampilkan <span className="text-monday-black font-bold">{totalItems === 0 ? 0 : startIndex + 1}</span> sampai <span className="text-monday-black font-bold">{endIndex}</span> dari <span className="text-monday-black font-bold">{totalItems}</span> dosen
          </p>
          <div className="flex items-center gap-2">
            <button
              onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
              disabled={currentPage === 1}
              className="p-2 border border-monday-border rounded-xl text-monday-gray hover:text-monday-black hover:bg-monday-gray-background disabled:opacity-50 disabled:pointer-events-none transition-300"
            >
              <ChevronLeft size={16} />
            </button>
            
            <div className="flex items-center gap-1.5">
              {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                <button
                  key={page}
                  onClick={() => setCurrentPage(page)}
                  className={`px-3 py-1 rounded-xl font-bold text-xs transition-300 ${
                    currentPage === page
                      ? 'bg-monday-blue text-white shadow-md shadow-monday-blue/15'
                      : 'border border-monday-border text-monday-gray hover:text-monday-black hover:bg-monday-gray-background'
                  }`}
                >
                  {page}
                </button>
              ))}
            </div>

            <button
              onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
              disabled={currentPage === totalPages}
              className="p-2 border border-monday-border rounded-xl text-monday-gray hover:text-monday-black hover:bg-monday-gray-background disabled:opacity-50 disabled:pointer-events-none transition-300"
            >
              <ChevronRight size={16} />
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
