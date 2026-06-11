import React, { useMemo } from 'react';
import { Building, Award, Users, GraduationCap, Star, BookOpen, TrendingUp, CheckCircle, Calendar } from 'lucide-react';
import MetricCard from './ui/MetricCard';
import WelcomeBanner from './ui/WelcomeBanner';

const DashboardTab = React.memo(function DashboardTab({
  user,
  faculties,
  studyPrograms,
  lecturers,
  students,
  activeSemester,
  kelasKuliahs,
  dosenPengampus,
  kelasMahasiswas = [],
  mataKuliahs = [],
  setActiveTab,
  mataKuliahMap = {},
  lecturerMap = {},
  studentMap = {},
  academicYearMap = {}
}) {
  const isMahasiswa = (user?.roles || []).some(r => r.name === 'mahasiswa');
  const isDosen = (user?.roles || []).some(r => r.name === 'dosen') && !isMahasiswa;

  // Compute student stats
  const studentMetrics = useMemo(() => {
    if (!isMahasiswa || !user) return null;
    const myMhs = students.find(m => m.user_id === user.id);
    if (!myMhs) return null;

    const studentEnrollments = kelasMahasiswas.filter(km => km.student_id === myMhs.id);

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
    const activeClasses = [];

    studentEnrollments.forEach(km => {
      const kk = kelasKuliahs.find(k => k.id === km.course_class_id);
      if (!kk) return;

      const mk = mataKuliahMap[kk.course_id];
      if (!mk) return;

      const sks = Number(mk.sks || 0);

      const isCurrentSemester = activeSemester && kk.academic_year_id === activeSemester.id;

      if (isCurrentSemester) {
        sksDiambilSemesterIni += sks;
        activeClasses.push(kk);
        if (km.letter_grade !== null && km.letter_grade !== undefined) {
          gradedSksSemester += sks;
          weightedSumSemester += sks * getGradeWeight(km.letter_grade);
        }
      }

      if (km.letter_grade !== null && km.letter_grade !== undefined) {
        gradedSksTotal += sks;
        weightedSumTotal += sks * getGradeWeight(km.letter_grade);
        if (km.letter_grade.toUpperCase().trim() !== 'E') {
          sksLulusTotal += sks;
        }
      }
    });

    const ips = gradedSksSemester > 0 ? (weightedSumSemester / gradedSksSemester).toFixed(2) : '0.00';
    const ipk = gradedSksTotal > 0 ? (weightedSumTotal / gradedSksTotal).toFixed(2) : '0.00';

    const daysIndo = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
    const todayName = daysIndo[new Date().getDay()];
    const todayClasses = activeClasses.filter(c => (c.day || '').toUpperCase().trim() === todayName);
    
    todayClasses.sort((a, b) => {
      const tA = a.start_time || '';
      const tB = b.start_time || '';
      return tA.localeCompare(tB);
    });

    return {
      ips,
      ipk,
      sksLulus: sksLulusTotal,
      sksDiambil: sksDiambilSemesterIni,
      todayClasses
    };
  }, [isMahasiswa, user, students, kelasMahasiswas, kelasKuliahs, mataKuliahMap, activeSemester]);

  // Compute dosen stats
  const dosenMetrics = useMemo(() => {
    if (!isDosen || !user) return null;
    const myDosen = lecturers.find(d => d.user_id === user.id);
    if (!myDosen) return null;

    // Total Kelas Diajar (active semester)
    const myTeachingAssignments = dosenPengampus.filter(dp => dp.lecturer_id === myDosen.id);
    const activeClasses = [];
    let totalSks = 0;
    
    myTeachingAssignments.forEach(dp => {
      const kk = kelasKuliahs.find(k => k.id === dp.course_class_id);
      if (kk && activeSemester && kk.academic_year_id === activeSemester.id) {
        activeClasses.push(kk);
        const mk = mataKuliahMap[kk.course_id];
        if (mk) totalSks += Number(mk.sks || 0);
      }
    });

    // Total Mahasiswa Diajar (di kelas aktif tersebut)
    const activeClassIds = activeClasses.map(k => k.id);
    const uniqueStudentsInClass = new Set();
    kelasMahasiswas.forEach(km => {
      if (activeClassIds.includes(km.course_class_id)) {
        uniqueStudentsInClass.add(km.student_id);
      }
    });

    // Mahasiswa Bimbingan
    const advisees = students.filter(s => s.academic_advisor_id === myDosen.id);

    // Jadwal Hari Ini
    const daysIndo = ['MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
    const todayName = daysIndo[new Date().getDay()];
    const todayClasses = activeClasses.filter(c => (c.day || '').toUpperCase().trim() === todayName);
    
    // Sort by start_time
    todayClasses.sort((a, b) => {
      const tA = a.start_time || '';
      const tB = b.start_time || '';
      return tA.localeCompare(tB);
    });

    return {
      totalKelas: activeClasses.length,
      totalSks,
      totalMahasiswaDiajar: uniqueStudentsInClass.size,
      totalMahasiswaBimbingan: advisees.length,
      todayClasses
    };
  }, [isDosen, user, lecturers, dosenPengampus, kelasKuliahs, mataKuliahMap, activeSemester, kelasMahasiswas, students]);

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
    if (isDosen && dosenMetrics) {
      return [
        { title: 'Kelas Diajar (Semester Ini)', count: dosenMetrics.totalKelas, icon: Building, colorClass: 'bg-monday-blue/10 text-monday-blue' },
        { title: 'Total SKS Diampu', count: `${dosenMetrics.totalSks} SKS`, icon: Award, colorClass: 'bg-violet-500/10 text-violet-600' },
        { title: 'Mahasiswa di Kelas', count: dosenMetrics.totalMahasiswaDiajar, icon: Users, colorClass: 'bg-emerald-500/10 text-emerald-600' },
        { title: 'Mahasiswa Bimbingan', count: dosenMetrics.totalMahasiswaBimbingan, icon: GraduationCap, colorClass: 'bg-amber-500/10 text-amber-600' },
      ];
    }
    return [
      { title: 'Total Fakultas', count: faculties.length, icon: Building, colorClass: 'bg-monday-blue/10 text-monday-blue' },
      { title: 'Program Studi', count: studyPrograms.length, icon: Award, colorClass: 'bg-violet-500/10 text-violet-600' },
      { title: 'Dosen Pengajar', count: lecturers.length, icon: Users, colorClass: 'bg-emerald-500/10 text-emerald-600' },
      { title: 'Mahasiswa Terdaftar', count: students.length, icon: GraduationCap, colorClass: 'bg-amber-500/10 text-amber-600' },
    ];
  }, [isMahasiswa, studentMetrics, isDosen, dosenMetrics, faculties, studyPrograms, lecturers, students]);

  return (
    <div className="space-y-6">
      {/* Summary Metric Widgets */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {metrics.map((stat, idx) => (
          <MetricCard 
            key={idx}
            title={stat.title}
            count={stat.count}
            icon={stat.icon}
            colorClass={stat.colorClass}
          />
        ))}
      </div>

      {/* Welcome banner */}
      <WelcomeBanner 
        title={
          isMahasiswa 
            ? 'Selamat Datang di SIAKAD Suzuran Student Portal' 
            : (user?.roles || []).some(r => r.name === 'dosen')
              ? 'Selamat Datang di SIAKAD Suzuran Lecturer Portal'
              : 'Selamat Datang di SIAKAD Suzuran Admin Portal'
        }
        description={
          isMahasiswa 
            ? 'Gunakan panel navigasi kiri untuk melihat jadwal kuliah mingguan Anda, melakukan registrasi kelas KRS, serta melihat hasil studi KHS di setiap semester.'
            : (user?.roles || []).some(r => r.name === 'dosen')
              ? 'Gunakan panel navigasi kiri untuk mengakses portal dosen aktif, memproses nilai KHS mahasiswa bimbingan, serta memantau jadwal kuliah kelas yang Anda ampu.'
              : 'Gunakan panel navigasi kiri untuk melakukan pengelolaan data akademik universitas. Anda dapat mengaktifkan semester berjalan, menugaskan tim dosen pengampu, mendaftarkan mahasiswa ke kelas, serta menginput nilai KHS di portal dosen aktif.'
        }
      />

      {/* Quick Academic Info */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {/* Active Semester widget */}
        <div className="bg-white border border-monday-border p-6 rounded-3xl space-y-4 lg:col-span-1 shadow-sm">
          <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">Semester Aktif</h4>
          {activeSemester ? (
            <div className="space-y-4">
              <div className="p-4 rounded-2xl bg-monday-lime-green/20 border border-monday-lime-green/30 text-center">
                <span className="text-2xl font-extrabold text-monday-black block">{activeSemester.code}</span>
                <span className="text-xs text-monday-gray font-bold uppercase mt-1 block">{activeSemester.name}</span>
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
          {(isDosen && dosenMetrics) || (isMahasiswa && studentMetrics) ? (
            <>
              <div className="flex items-center justify-between">
                <h4 className="font-bold text-xs text-monday-gray uppercase tracking-wider">
                  {isMahasiswa ? 'Jadwal Kuliah Hari Ini' : 'Jadwal Mengajar Hari Ini'}
                </h4>
                <span className="text-xs font-bold text-monday-blue bg-monday-blue/10 px-2.5 py-1 rounded-lg">
                  {new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' })}
                </span>
              </div>
              
              {(isMahasiswa ? studentMetrics.todayClasses : dosenMetrics.todayClasses).length > 0 ? (
                <div className="space-y-3 mt-4">
                  {(isMahasiswa ? studentMetrics.todayClasses : dosenMetrics.todayClasses).map(cls => {
                    const mk = mataKuliahMap[cls.course_id];
                    return (
                      <div key={cls.id} className="flex items-center justify-between p-4 bg-monday-gray-background/50 border border-monday-border rounded-2xl hover:border-monday-blue transition-colors">
                        <div className="flex items-center gap-3">
                          <div className="bg-monday-blue/10 p-2.5 rounded-xl text-monday-blue">
                            <Calendar size={18} />
                          </div>
                          <div>
                            <span className="font-bold text-sm text-monday-black block">{mk ? mk.name : 'Unknown Course'}</span>
                            <span className="text-xs text-monday-gray font-semibold block mt-0.5">Kelas {cls.class_code} • {cls.room}</span>
                          </div>
                        </div>
                        <div className="text-right">
                          <span className="font-extrabold text-sm text-monday-blue block">{cls.start_time.slice(0,5)} - {cls.end_time.slice(0,5)}</span>
                        </div>
                      </div>
                    );
                  })}
                </div>
              ) : (
                <div className="p-6 bg-monday-gray-background/30 rounded-2xl border border-dashed border-monday-border text-center">
                  <p className="text-monday-gray text-xs font-bold">
                    {isMahasiswa ? 'Tidak ada jadwal kuliah hari ini.' : 'Tidak ada jadwal mengajar hari ini.'}
                  </p>
                </div>
              )}
            </>
          ) : (
            <>
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
            </>
          )}

          {!isMahasiswa && (
            <div className={`p-4 bg-monday-blue/10 rounded-2xl border border-monday-blue/20 flex items-center justify-between text-xs gap-3 ${isDosen && dosenMetrics ? 'mt-4' : ''}`}>
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
});

export default DashboardTab;
