import React from 'react';
import { Home, Building, Award, Calendar, Users, GraduationCap, BookOpen, Layers, Briefcase, Edit3 } from 'lucide-react';

export default function Sidebar({ activeTab, setActiveTab, setSearchQuery }) {
  return (
    <aside className="relative flex h-auto w-[280px] shrink-0 bg-white border-r border-monday-border">
      <div className="flex flex-col fixed top-0 w-[280px] shrink-0 h-screen pt-[30px] px-4 gap-[24px]">
        
        {/* Logo Brand */}
        <div className="px-4 flex items-center gap-3">
          <div className="bg-monday-blue p-2.5 rounded-2xl text-white">
            <Layers size={22} />
          </div>
          <div>
            <h1 className="font-extrabold text-xl text-monday-black uppercase tracking-tight">SIAKAD</h1>
            <p className="text-[9px] text-monday-gray font-extrabold tracking-widest uppercase">SUZURAN PORTAL</p>
          </div>
        </div>

        <hr className="border-monday-border mx-4" />

        {/* Nav List */}
        <div className="flex flex-col gap-4 overflow-y-auto hide-scrollbar h-full pb-6">
          <p className="font-bold text-xs text-monday-gray uppercase tracking-wider px-4">Main Menu</p>
          <nav className="space-y-1">
            {[
              { id: 'dashboard', label: 'Dashboard', icon: Home },
              { id: 'fakultas', label: 'Fakultas', icon: Building },
              { id: 'prodi', label: 'Program Studi', icon: Award },
              { id: 'tahun-akademik', label: 'Tahun Akademik', icon: Calendar },
              { id: 'dosen', label: 'Data Dosen', icon: Users },
              { id: 'mahasiswa', label: 'Data Mahasiswa', icon: GraduationCap },
              { id: 'mata-kuliah', label: 'Mata Kuliah', icon: BookOpen },
              { id: 'kelas-kuliah', label: 'Kelas Kuliah', icon: Layers },
            ].map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => {
                    setActiveTab(tab.id);
                    setSearchQuery('');
                  }}
                  className={`w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-300 ${
                    isActive 
                      ? 'bg-monday-blue/10 text-monday-blue' 
                      : 'text-monday-black hover:bg-monday-gray-background'
                  }`}
                >
                  <Icon size={18} className={isActive ? 'text-monday-blue' : 'text-monday-black'} />
                  <span className="flex-1 text-left">{tab.label}</span>
                  {isActive && <div className="w-1.5 h-6 rounded-l-md bg-monday-blue ml-auto"></div>}
                </button>
              );
            })}
          </nav>

          <p className="font-bold text-xs text-monday-gray uppercase tracking-wider px-4 mt-2">Portal & Nilai</p>
          <nav className="space-y-1">
            {[
              { id: 'lecturer-portal', label: 'Portal Dosen (Aktif)', icon: Briefcase },
              { id: 'kelas-mahasiswa', label: 'KRS & Nilai KHS', icon: Edit3 },
            ].map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => {
                    setActiveTab(tab.id);
                    setSearchQuery('');
                  }}
                  className={`w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-300 ${
                    isActive 
                      ? 'bg-monday-blue/10 text-monday-blue' 
                      : 'text-monday-black hover:bg-monday-gray-background'
                  }`}
                >
                  <Icon size={18} className={isActive ? 'text-monday-blue' : 'text-monday-black'} />
                  <span className="flex-1 text-left">{tab.label}</span>
                  {isActive && <div className="w-1.5 h-6 rounded-l-md bg-monday-blue ml-auto"></div>}
                </button>
              );
            })}
          </nav>
        </div>

        {/* Footer Info */}
        <div className="p-4 border-t border-monday-border text-center bg-white mt-auto">
          <p className="text-xs text-monday-gray font-bold">SIAKAD v1.0.0 &copy; 2026</p>
          <p className="text-[10px] text-monday-blue font-extrabold mt-0.5">Antigravity Design</p>
        </div>

      </div>
    </aside>
  );
}
