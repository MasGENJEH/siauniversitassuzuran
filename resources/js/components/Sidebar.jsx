import React from 'react';
import { Home, Building, Award, Calendar, Users, GraduationCap, BookOpen, Layers, Briefcase, Edit3, LogOut, User } from 'lucide-react';

export default function Sidebar({ activeTab, setActiveTab, setSearchQuery, user, onLogout }) {
  const roles = user?.roles || [];

  const mainMenuItems = [
    { id: 'dashboard', label: 'Dashboard', icon: Home, roles: ['admin', 'dosen', 'mahasiswa'] },
    { id: 'fakultas', label: 'Fakultas', icon: Building, roles: ['admin'] },
    { id: 'prodi', label: 'Program Studi', icon: Award, roles: ['admin'] },
    { id: 'tahun-akademik', label: 'Tahun Akademik', icon: Calendar, roles: ['admin'] },
    { id: 'dosen', label: 'Data Dosen', icon: Users, roles: ['admin'] },
    { id: 'mahasiswa', label: 'Data Mahasiswa', icon: GraduationCap, roles: ['admin'] },
    { id: 'mata-kuliah', label: 'Mata Kuliah', icon: BookOpen, roles: ['admin'] },
    { id: 'kelas-kuliah', label: 'Kelas Kuliah', icon: Layers, roles: ['admin'] },
  ].filter(item => item.roles.some(role => roles.some(r => r.name === role)));

  const portalMenuItems = [
    { id: 'lecturer-portal', label: 'Portal Dosen (Aktif)', icon: Briefcase, roles: ['admin', 'dosen'] },
    { id: 'kelas-mahasiswa', label: 'KRS & Nilai KHS', icon: Edit3, roles: ['admin', 'mahasiswa'] },
    { id: 'jadwal-kuliah', label: 'Jadwal Kuliah', icon: Calendar, roles: ['mahasiswa'] },
    { id: 'profil-mahasiswa', label: 'Profil Saya', icon: User, roles: ['mahasiswa'] },
  ].filter(item => item.roles.some(role => roles.some(r => r.name === role)));

  return (
    <aside className="relative flex h-auto w-[280px] shrink-0 bg-white border-r border-monday-border print:hidden">
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
        <div className="flex flex-col gap-4 overflow-y-auto hide-scrollbar h-full pb-[100px]">
          {mainMenuItems.length > 0 && (
            <>
              <p className="font-bold text-xs text-monday-gray uppercase tracking-wider px-4">Main Menu</p>
              <nav className="space-y-1">
                {mainMenuItems.map((tab) => {
                  const Icon = tab.icon;
                  const isActive = activeTab === tab.id;
                  return (
                    <button
                      key={tab.id}
                      onClick={() => {
                        setActiveTab(tab.id);
                        setSearchQuery('');
                      }}
                      className={`w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-300 cursor-pointer ${
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
            </>
          )}

          {portalMenuItems.length > 0 && (
            <>
              <p className="font-bold text-xs text-monday-gray uppercase tracking-wider px-4 mt-2">Portal & Nilai</p>
              <nav className="space-y-1">
                {portalMenuItems.map((tab) => {
                  const Icon = tab.icon;
                  const isActive = activeTab === tab.id;
                  return (
                    <button
                      key={tab.id}
                      onClick={() => {
                        setActiveTab(tab.id);
                        setSearchQuery('');
                      }}
                      className={`w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-300 cursor-pointer ${
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
            </>
          )}
        </div>

        {/* Action Panel & Footer */}
        <div className="flex flex-col gap-2 bg-white pt-2 border-t border-monday-border mt-auto">
          {/* Logout Button */}
          <button
            onClick={onLogout}
            className="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-bold text-monday-red hover:bg-monday-red/10 transition-300 cursor-pointer"
          >
            <LogOut size={18} className="text-monday-red" />
            <span className="flex-1 text-left">Keluar Akun</span>
          </button>
          
          <div className="p-4 text-center">
            <p className="text-xs text-monday-gray font-bold">SIAKAD v1.0.0 &copy; 2026</p>
            <p className="text-[10px] text-monday-blue font-extrabold mt-0.5">Antigravity Design</p>
          </div>
        </div>

      </div>
    </aside>
  );
}
