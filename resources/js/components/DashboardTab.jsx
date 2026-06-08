import React from 'react';
import { Building, Award, Users, GraduationCap, Star } from 'lucide-react';

export default function DashboardTab({
  user,
  fakultas,
  prodis,
  dosens,
  mahasiswas,
  activeSemester,
  kelasKuliahs,
  dosenPengampus,
  setActiveTab
}) {
  const isMahasiswa = (user?.roles || []).some(r => r.name === 'mahasiswa');
  return (
    <div className="space-y-6">
      {/* Summary Metric Widgets */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {[
          { title: 'Total Fakultas', count: fakultas.length, icon: Building, colorClass: 'bg-monday-blue/10 text-monday-blue' },
          { title: 'Program Studi', count: prodis.length, icon: Award, colorClass: 'bg-violet-500/10 text-violet-600' },
          { title: 'Dosen Pengajar', count: dosens.length, icon: Users, colorClass: 'bg-emerald-500/10 text-emerald-600' },
          { title: 'Mahasiswa Terdaftar', count: mahasiswas.length, icon: GraduationCap, colorClass: 'bg-amber-500/10 text-amber-600' },
        ].map((stat, idx) => (
          <div key={idx} className="bg-white border border-monday-border p-6 rounded-3xl flex items-center justify-between shadow-sm hover:-translate-y-0.5 transition-300">
            <div className="space-y-1">
              <p className="text-xs font-bold text-monday-gray uppercase tracking-wider">{stat.title}</p>
              <h3 className="text-3xl font-extrabold text-monday-black">{stat.count}</h3>
            </div>
            <div className={`flex size-14 rounded-full ${stat.colorClass} items-center justify-center`}>
              <stat.icon size={24} />
            </div>
          </div>
        ))}
      </div>

      {/* Welcome banner */}
      <div className="blue-gradient border border-monday-blue/20 p-6 rounded-3xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 text-white">
        <div className="flex gap-4">
          <div className="bg-white/10 p-3 rounded-2xl text-monday-lime-green border border-white/20 shrink-0">
            <Star size={24} className="text-monday-lime-green" />
          </div>
          <div>
            <h4 className="font-extrabold text-lg text-white">Selamat Datang di SIAKAD Suzuran Admin Portal</h4>
            <p className="text-xs text-monday-lime-green-char mt-1 max-w-2xl leading-relaxed">
              Gunakan panel navigasi kiri untuk melakukan pengelolaan data akademik universitas. Anda dapat mengaktifkan semester berjalan, menugaskan tim dosen pengampu, mendaftarkan mahasiswa ke kelas, serta menginput nilai KHS di portal dosen aktif.
            </p>
          </div>
        </div>
      </div>

      {/* Quick Academic Info */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {/* Active Semester widget */}
        <div className="bg-white border border-monday-border p-6 rounded-3xl space-y-4 lg:col-span-1 shadow-sm">
          <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Semester Aktif</h4>
          {activeSemester ? (
            <div className="space-y-4">
              <div className="p-4 rounded-2xl bg-monday-lime-green/20 border border-monday-lime-green/30 text-center">
                <span className="text-2xl font-extrabold text-monday-black block">{activeSemester.kode_ta}</span>
                <span className="text-xs text-monday-gray font-bold uppercase mt-1 block">{activeSemester.nama_ta}</span>
              </div>
              <p className="text-xs text-monday-gray font-semibold leading-relaxed">
                Seluruh penugasan dosen pengampu dan pengambilan kelas kuliah mahasiswa terikat pada semester aktif ini.
              </p>
            </div>
          ) : (
            <div className="p-4 rounded-2xl bg-monday-red/10 border border-monday-red/20 text-center text-monday-red text-xs font-bold leading-normal">
              Belum ada semester aktif! Silakan pilih tab "Tahun Akademik" untuk mengaktifkan salah satu semester akademik.
            </div>
          )}
        </div>

        {/* Classes details & portal helper */}
        <div className="bg-white border border-monday-border p-6 rounded-3xl space-y-4 lg:col-span-2 shadow-sm">
          <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Statistik Kelas Kuliah & Pengampuan</h4>
          <div className="grid grid-cols-2 gap-4">
            <div className="p-4 bg-monday-background rounded-2xl border border-monday-border">
              <span className="text-monday-gray text-xs block font-bold">Total Kelas Kuliah</span>
              <span className="text-2xl font-extrabold text-monday-black block mt-1">{kelasKuliahs.length} kelas</span>
            </div>
            <div className="p-4 bg-monday-background rounded-2xl border border-monday-border">
              <span className="text-monday-gray text-xs block font-bold">Total Hubungan Pengampu</span>
              <span className="text-2xl font-extrabold text-monday-black block mt-1">{dosenPengampus.length} penugasan</span>
            </div>
          </div>
          {!isMahasiswa && (
            <div className="p-4 bg-monday-blue/10 rounded-2xl border border-monday-blue/20 flex items-center justify-between text-xs gap-3">
              <span className="text-monday-gray font-semibold">Gunakan portal pengampu dosen aktif untuk memproses nilai KHS.</span>
              <button
                onClick={() => setActiveTab('lecturer-portal')}
                className="px-4 py-2 bg-monday-blue text-white rounded-full font-bold text-xs hover:bg-opacity-90 transition-300 text-nowrap"
              >
                Masuk Portal Dosen
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
