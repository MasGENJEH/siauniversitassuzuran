import React from 'react';
import { RefreshCw, GraduationCap } from 'lucide-react';

export default function Header({ activeTab, activeSemester, loading, fetchData }) {
  return (
    <header className="flex items-center w-full gap-6 mt-[30px] mb-6 px-8 shrink-0">
      <div className="flex items-center gap-6 h-[92px] bg-white w-full rounded-3xl p-[18px] border border-monday-border shadow-sm">
        <div className="flex flex-col gap-[4px] w-full">
          <h1 className="font-extrabold text-2xl text-monday-black capitalize">
            {activeTab.replace('-', ' ')}
          </h1>
        </div>
        
        <div className="flex items-center flex-nowrap gap-3">
          {activeSemester ? (
            <div className="flex items-center gap-2 px-4 py-2 rounded-full bg-monday-lime-green/20 border border-monday-lime-green/30 text-monday-black text-xs font-bold text-nowrap">
              <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
              </span>
              Semester Aktif: {activeSemester.nama_ta}
            </div>
          ) : (
            <div className="px-4 py-2 rounded-full bg-monday-red/10 border border-monday-red/20 text-monday-red text-xs font-bold text-nowrap">
              Semester Aktif: Belum Ada
            </div>
          )}

          <button 
            onClick={fetchData} 
            title="Sync database data" 
            className="flex size-14 rounded-full bg-monday-gray-background items-center justify-center overflow-hidden hover:bg-opacity-80 transition-300 shrink-0"
          >
            <RefreshCw size={20} className={`text-monday-black ${loading ? 'animate-spin' : ''}`} />
          </button>
        </div>
      </div>

      {/* User Profile Card */}
      <div className="flex items-center shrink-0 h-[92px] bg-white rounded-3xl p-[18px] border border-monday-border gap-4">
        <div className="flex size-14 rounded-full bg-monday-blue items-center justify-center overflow-hidden shrink-0">
          <GraduationCap size={24} className="text-white" />
        </div>
        <div className="flex flex-col gap-[2px]">
          <p className="font-bold text-base leading-tight text-monday-black">Suzuran Admin</p>
          <p className="text-xs text-monday-gray font-semibold">Super Administrator</p>
        </div>
      </div>
    </header>
  );
}
