import React, { useMemo } from 'react';
import { Building, Award, Users, GraduationCap, Star, BookOpen, TrendingUp, CheckCircle, Calendar } from 'lucide-react';

export default function DashboardTab({
  user,
  fakultas,
  prodis,
  dosens,
  mahasiswas,
  activeSemester,
  kelasKuliahs,
  dosenPengampus,
  kelasMahasiswas = [],
  mataKuliahs = [],
  setActiveTab
}) {
  const isMahasiswa = (user?.roles || []).some(r => r.name === 'mahasiswa');

  // Compute student stats
  const studentMetrics = useMemo(() => {
    if (!isMahasiswa || !user) return null;
    const myMhs = mahasiswas.find(m => m.id_user === user.id);
    if (!myMhs) return null;

    const studentEnrollments = kelasMahasiswas.filter(km => km.id_mahasiswa === myMhs.id);

    const getGradeWeight = (letter) => {
      const char = (letter || '').toUpperCase().trim();
      switch (char) {
        case 'A': return 4.0;
        case 'B': return 3.0;
        case 'C': return 2.0;
        case 'D': return 1.0;
        case 'E': return 0.0;
        default: return 0.0;
      }
    };

    let sksDiambilSemesterIni = 0;
    let sksLulusTotal = 0;
    let gradedSksSemester = 0;
    let weightedSumSemester = 0;
    let gradedSksTotal = 0;
    let weightedSumTotal = 0;

    studentEnrollments.forEach(km => {
      const kk = kelasKuliahs.find(k => k.id === km.id_kelas);
      if (!kk) return;

      const mk = mataKuliahs.find(m => m.id === kk.id_mk);
      if (!mk) return;

      const sks = Number(mk.sks || 0);

      const isCurrentSemester = activeSemester && kk.id_ta === activeSemester.id;

      if (isCurrentSemester) {
        sksDiambilSemesterIni += sks;
        if (km.nilai_huruf !== null && km.nilai_huruf !== undefined) {
          gradedSksSemester += sks;
          weightedSumSemester += sks * getGradeWeight(km.nilai_huruf);
        }
      }

      if (km.nilai_huruf !== null && km.nilai_huruf !== undefined) {
        gradedSksTotal += sks;
        weightedSumTotal += sks * getGradeWeight(km.nilai_huruf);
        if (km.nilai_huruf.toUpperCase().trim() !== 'E') {
          sksLulusTotal += sks;
        }
      }
    });

    const ips = gradedSksSemester > 0 ? (weightedSumSemester / gradedSksSemester).toFixed(2) : '0.00';
    const ipk = gradedSksTotal > 0 ? (weightedSumTotal / gradedSksTotal).toFixed(2) : '0.00';

    return {
      ips,
      ipk,
      sksLulus: sksLulusTotal,
      sksDiambil: sksDiambilSemesterIni
    };
  }, [isMahasiswa, user, mahasiswas, kelasMahasiswas, kelasKuliahs, mataKuliahs, activeSemester]);

  // Determine which metrics cards to render
  const metrics = useMemo(() => {
    if (isMahasiswa && studentMetrics) {
      return [
        { title: 'Indeks Prestasi Kumulatif (IPK)', count: studentMetrics.ipk, icon: TrendingUp, colorClass: 'bg-monday-blue/10 text-monday-blue' },
        { title: 'Indeks Prestasi Semester (IPS)', count: studentMetrics.ips, icon: Star, colorClass: 'bg-violet-500/10 text-violet-600' },
        { title: 'Total SKS Lulus', count: `${studentMetrics.sksLulus} SKS`, icon: CheckCircle, colorClass: 'bg-emerald-500/10 text-emerald-600' },
        { title: 'SKS Diambil Semester Ini', count: `${studentMetrics.sksDiambil} SKS`, icon: BookOpen, colorClass: 'bg-amber-500/10 text-amber-600' },
      ];
    }
    return [
      { title: 'Total Fakultas', count: fakultas.length, icon: Building, colorClass: 'bg-monday-blue/10 text-monday-blue' },
      { title: 'Program Studi', count: prodis.length, icon: Award, colorClass: 'bg-violet-500/10 text-violet-600' },
      { title: 'Dosen Pengajar', count: dosens.length, icon: Users, colorClass: 'bg-emerald-500/10 text-emerald-600' },
      { title: 'Mahasiswa Terdaftar', count: mahasiswas.length, icon: GraduationCap, colorClass: 'bg-amber-500/10 text-amber-600' },
    ];
  }, [isMahasiswa, studentMetrics, fakultas, prodis, dosens, mahasiswas]);

  return (
    <div className="space-y-6">
      {/* Summary Metric Widgets */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {metrics.map((stat, idx) => (
          <div key={idx} className="bg-white border border-monday-border p-6 rounded-3xl flex items-center justify-between shadow-sm hover:-translate-y-0.5 transition-300">
            <div className="space-y-1">
              <p className="text-xs font-bold text-monday-gray uppercase tracking-wider">{stat.title}</p>
              <h3 className="text-2xl font-extrabold text-monday-black">{stat.count}</h3>
            </div>
            <div className={`flex size-14 rounded-full ${stat.colorClass} items-center justify-center shrink-0`}>
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
            <h4 className="font-extrabold text-lg text-white">
              {isMahasiswa 
                ? 'Selamat Datang di SIAKAD Suzuran Student Portal' 
                : (user?.roles || []).some(r => r.name === 'dosen')
                  ? 'Selamat Datang di SIAKAD Suzuran Lecturer Portal'
                  : 'Selamat Datang di SIAKAD Suzuran Admin Portal'}
            </h4>
            <p className="text-xs text-monday-lime-green-char mt-1 max-w-2xl leading-relaxed">
              {isMahasiswa 
                ? 'Gunakan panel navigasi kiri untuk melihat jadwal kuliah mingguan Anda, melakukan registrasi kelas KRS, serta melihat hasil studi KHS di setiap semester.'
                : (user?.roles || []).some(r => r.name === 'dosen')
                  ? 'Gunakan panel navigasi kiri untuk mengakses portal dosen aktif, memproses nilai KHS mahasiswa bimbingan, serta memantau jadwal kuliah kelas yang Anda ampu.'
                  : 'Gunakan panel navigasi kiri untuk melakukan pengelolaan data akademik universitas. Anda dapat mengaktifkan semester berjalan, menugaskan tim dosen pengampu, mendaftarkan mahasiswa ke kelas, serta menginput nilai KHS di portal dosen aktif.'}
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
