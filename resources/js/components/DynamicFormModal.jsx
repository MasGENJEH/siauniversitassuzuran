import React from 'react';
import { X } from 'lucide-react';

export default function DynamicFormModal({
  showModal,
  setShowModal,
  modalType,
  modalAction,
  selectedItem,
  formData,
  setFormData,
  formErrors,
  fakultas,
  prodis,
  tahunAkademiks,
  dosens,
  mahasiswas,
  mataKuliahs,
  kelasKuliahs,
  users,
  handleFormSubmit
}) {
  if (!showModal) return null;

  const handleInputChange = (field, val) => {
    setFormData(prev => ({ ...prev, [field]: val }));
  };

  const renderFormFields = () => {
    switch (modalType) {
      case 'fakultas':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Kode Fakultas</label>
              <input 
                type="text" 
                value={formData.kode_fakultas || ''} 
                onChange={(e) => handleInputChange('kode_fakultas', e.target.value.toUpperCase())}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: FT"
              />
              {formErrors.kode_fakultas && <p className="text-xs text-monday-red font-bold">{formErrors.kode_fakultas[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Fakultas</label>
              <input 
                type="text" 
                value={formData.nama_fakultas || ''} 
                onChange={(e) => handleInputChange('nama_fakultas', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: FAKULTAS TEKNIK"
              />
              {formErrors.nama_fakultas && <p className="text-xs text-monday-red font-bold">{formErrors.nama_fakultas[0]}</p>}
            </div>
          </>
        );

      case 'prodi':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Fakultas Induk</label>
              <select 
                value={formData.id_fakultas || ''} 
                onChange={(e) => handleInputChange('id_fakultas', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Fakultas --</option>
                {fakultas.map(f => (
                  <option key={f.id} value={f.id}>{f.nama_fakultas}</option>
                ))}
              </select>
              {formErrors.id_fakultas && <p className="text-xs text-monday-red font-bold">{formErrors.id_fakultas[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Kode Prodi</label>
              <input 
                type="text" 
                value={formData.kode_prodi || ''} 
                onChange={(e) => handleInputChange('kode_prodi', e.target.value.toUpperCase())}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: IF"
              />
              {formErrors.kode_prodi && <p className="text-xs text-monday-red font-bold">{formErrors.kode_prodi[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Program Studi</label>
              <input 
                type="text" 
                value={formData.nama_prodi || ''} 
                onChange={(e) => handleInputChange('nama_prodi', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: TEKNIK INFORMATIKA"
              />
              {formErrors.nama_prodi && <p className="text-xs text-monday-red font-bold">{formErrors.nama_prodi[0]}</p>}
            </div>
          </>
        );

      case 'tahunAkademik':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Kode TA (Semester)</label>
              <input 
                type="text" 
                value={formData.kode_ta || ''} 
                onChange={(e) => handleInputChange('kode_ta', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: 20241"
              />
              {formErrors.kode_ta && <p className="text-xs text-monday-red font-bold">{formErrors.kode_ta[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama TA / Label</label>
              <input 
                type="text" 
                value={formData.nama_ta || ''} 
                onChange={(e) => handleInputChange('nama_ta', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: GANJIL 2024/2025"
              />
              {formErrors.nama_ta && <p className="text-xs text-monday-red font-bold">{formErrors.nama_ta[0]}</p>}
            </div>
            {modalAction === 'edit' && (
              <div className="flex items-center gap-3">
                <input 
                  type="checkbox" 
                  id="status-ta"
                  checked={formData.status || false} 
                  onChange={(e) => handleInputChange('status', e.target.checked)}
                  className="rounded border-monday-border text-monday-blue focus:ring-monday-blue"
                />
                <label htmlFor="status-ta" className="text-xs font-bold text-monday-gray uppercase tracking-wider cursor-pointer">
                  Aktifkan Semester Ini
                </label>
              </div>
            )}
          </>
        );

      case 'dosen':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">NIDN Dosen</label>
              <input 
                type="text" 
                value={formData.nidn || ''} 
                onChange={(e) => handleInputChange('nidn', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: 041234567"
              />
              {formErrors.nidn && <p className="text-xs text-monday-red font-bold">{formErrors.nidn[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Lengkap Dosen</label>
              <input 
                type="text" 
                value={formData.nama || ''} 
                onChange={(e) => handleInputChange('nama', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: Dr. Fachri, M.T."
              />
              {formErrors.nama && <p className="text-xs text-monday-red font-bold">{formErrors.nama[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Tautkan Akun User</label>
              <select 
                value={formData.id_user || ''} 
                onChange={(e) => handleInputChange('id_user', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Akun User --</option>
                {users.map(u => (
                  <option key={u.id} value={u.id}>{u.username} ({u.email})</option>
                ))}
              </select>
              {formErrors.id_user && <p className="text-xs text-monday-red font-bold">{formErrors.id_user[0]}</p>}
            </div>
          </>
        );

      case 'mahasiswa':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">NIM Mahasiswa</label>
              <input 
                type="text" 
                value={formData.nim || ''} 
                onChange={(e) => handleInputChange('nim', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: 41234567"
              />
              {formErrors.nim && <p className="text-xs text-monday-red font-bold">{formErrors.nim[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Lengkap Mahasiswa</label>
              <input 
                type="text" 
                value={formData.nama || ''} 
                onChange={(e) => handleInputChange('nama', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: Fachri Hanz"
              />
              {formErrors.nama && <p className="text-xs text-monday-red font-bold">{formErrors.nama[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Program Studi</label>
              <select 
                value={formData.id_prodi || ''} 
                onChange={(e) => handleInputChange('id_prodi', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Program Studi --</option>
                {prodis.map(p => (
                  <option key={p.id} value={p.id}>{p.nama_prodi}</option>
                ))}
              </select>
              {formErrors.id_prodi && <p className="text-xs text-monday-red font-bold">{formErrors.id_prodi[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Dosen Wali Akademik</label>
              <select 
                value={formData.id_dosen_pa || ''} 
                onChange={(e) => handleInputChange('id_dosen_pa', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Dosen Wali --</option>
                {dosens.map(d => (
                  <option key={d.id} value={d.id}>{d.nama}</option>
                ))}
              </select>
              {formErrors.id_dosen_pa && <p className="text-xs text-monday-red font-bold">{formErrors.id_dosen_pa[0]}</p>}
            </div>
          </>
        );

      case 'mataKuliah':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Program Studi</label>
              <select 
                value={formData.id_prodi || ''} 
                onChange={(e) => handleInputChange('id_prodi', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Program Studi --</option>
                {prodis.map(p => (
                  <option key={p.id} value={p.id}>{p.nama_prodi}</option>
                ))}
              </select>
              {formErrors.id_prodi && <p className="text-xs text-monday-red font-bold">{formErrors.id_prodi[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Kode Mata Kuliah</label>
              <input 
                type="text" 
                value={formData.kode_mk || ''} 
                onChange={(e) => handleInputChange('kode_mk', e.target.value.toUpperCase())}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: MK001"
              />
              {formErrors.kode_mk && <p className="text-xs text-monday-red font-bold">{formErrors.kode_mk[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Mata Kuliah</label>
              <input 
                type="text" 
                value={formData.nama_mk || ''} 
                onChange={(e) => handleInputChange('nama_mk', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: ALGORITMA & PEMROGRAMAN"
              />
              {formErrors.nama_mk && <p className="text-xs text-monday-red font-bold">{formErrors.nama_mk[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Bobot SKS</label>
              <input 
                type="number" 
                min="1"
                max="6"
                value={formData.sks || ''} 
                onChange={(e) => handleInputChange('sks', e.target.value === '' ? '' : Number(e.target.value))}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: 3"
              />
              {formErrors.sks && <p className="text-xs text-monday-red font-bold">{formErrors.sks[0]}</p>}
            </div>
          </>
        );

      case 'kelasKuliah':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Mata Kuliah</label>
              <select 
                value={formData.id_mk || ''} 
                onChange={(e) => handleInputChange('id_mk', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Mata Kuliah --</option>
                {mataKuliahs.map(m => (
                  <option key={m.id} value={m.id}>{m.kode_mk} - {m.nama_mk}</option>
                ))}
              </select>
              {formErrors.id_mk && <p className="text-xs text-monday-red font-bold">{formErrors.id_mk[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Tahun Akademik</label>
              <select 
                value={formData.id_ta || ''} 
                onChange={(e) => handleInputChange('id_ta', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih TA --</option>
                {tahunAkademiks.map(t => (
                  <option key={t.id} value={t.id}>{t.nama_ta}</option>
                ))}
              </select>
              {formErrors.id_ta && <p className="text-xs text-monday-red font-bold">{formErrors.id_ta[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Kelas</label>
              <input 
                type="text" 
                value={formData.nama_kelas || ''} 
                onChange={(e) => handleInputChange('nama_kelas', e.target.value.toUpperCase())}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                placeholder="Contoh: A, B, ATAU REGULER"
              />
              {formErrors.nama_kelas && <p className="text-xs text-monday-red font-bold">{formErrors.nama_kelas[0]}</p>}
            </div>
          </>
        );

      case 'dosenPengampu':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nama Kelas Kuliah</label>
              <input 
                type="text" 
                value={(() => {
                  const k = kelasKuliahs.find(cls => cls.id === formData.id_kelas);
                  const mk = k ? mataKuliahs.find(m => m.id === k.id_mk) : null;
                  return k && mk ? `${mk.nama_mk} (Kelas ${k.nama_kelas})` : '';
                })()} 
                disabled
                className="w-full px-4 py-2.5 bg-monday-background border border-monday-border rounded-xl text-sm font-semibold text-monday-gray cursor-not-allowed"
              />
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Pilih Dosen Pengampu</label>
              <select 
                value={formData.id_dosen || ''} 
                onChange={(e) => handleInputChange('id_dosen', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
              >
                <option value="">-- Pilih Dosen --</option>
                {dosens.map(d => (
                  <option key={d.id} value={d.id}>{d.nama} (NIDN: {d.nidn})</option>
                ))}
              </select>
              {formErrors.id_dosen && <p className="text-xs text-monday-red font-bold">{formErrors.id_dosen[0]}</p>}
            </div>
          </>
        );

      case 'kelasMahasiswa':
        return (
          <>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Mahasiswa</label>
              <select 
                value={formData.id_mahasiswa || ''} 
                onChange={(e) => handleInputChange('id_mahasiswa', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                disabled={modalAction === 'edit'}
              >
                <option value="">-- Pilih Mahasiswa --</option>
                {mahasiswas.map(m => (
                  <option key={m.id} value={m.id}>{m.nim} - {m.nama}</option>
                ))}
              </select>
              {formErrors.id_mahasiswa && <p className="text-xs text-monday-red font-bold">{formErrors.id_mahasiswa[0]}</p>}
            </div>
            <div className="space-y-1.5">
              <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Kelas Kuliah</label>
              <select 
                value={formData.id_kelas || ''} 
                onChange={(e) => handleInputChange('id_kelas', e.target.value)}
                className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                disabled={modalAction === 'edit'}
              >
                <option value="">-- Pilih Kelas Kuliah --</option>
                {kelasKuliahs.map(k => {
                  const mk = mataKuliahs.find(m => m.id === k.id_mk);
                  const ta = tahunAkademiks.find(t => t.id === k.id_ta);
                  return (
                    <option key={k.id} value={k.id}>
                      {mk ? mk.nama_mk : 'N/A'} (Kelas {k.nama_kelas}) - {ta ? ta.nama_ta : 'N/A'}
                    </option>
                  );
                })}
              </select>
              {formErrors.id_kelas && <p className="text-xs text-monday-red font-bold">{formErrors.id_kelas[0]}</p>}
            </div>
            {modalAction === 'edit' && (
              <div className="space-y-1.5">
                <label className="text-xs font-bold text-monday-gray uppercase tracking-wider block">Nilai Angka (KHS)</label>
                <input 
                  type="number" 
                  min="0"
                  max="100"
                  value={formData.nilai_angka === null ? '' : formData.nilai_angka} 
                  onChange={(e) => handleInputChange('nilai_angka', e.target.value === '' ? null : Number(e.target.value))}
                  className="w-full px-4 py-2.5 bg-white border border-monday-border rounded-xl text-sm focus:outline-none focus:border-monday-black font-semibold text-monday-black"
                  placeholder="Nilai Angka (0 - 100)"
                />
                {formErrors.nilai_angka && <p className="text-xs text-monday-red font-bold">{formErrors.nilai_angka[0]}</p>}
              </div>
            )}
          </>
        );

      default:
        return null;
    }
  };

  const getModalTitle = () => {
    const act = modalAction === 'create' ? 'Tambah' : modalAction === 'edit' ? 'Ubah' : 'Atur';
    let typeName = modalType;
    if (modalType === 'tahunAkademik') typeName = 'Tahun Akademik';
    if (modalType === 'mataKuliah') typeName = 'Mata Kuliah';
    if (modalType === 'kelasKuliah') typeName = 'Kelas Kuliah';
    if (modalType === 'dosenPengampu') typeName = 'Tim Pengampu Dosen';
    if (modalType === 'kelasMahasiswa') typeName = 'Pendaftaran Kelas (KRS)';
    
    return `${act} Data ${typeName}`;
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#292D32B2] backdrop-blur-sm">
      <div className="w-full max-w-lg bg-white rounded-3xl border border-monday-border shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div className="flex items-center justify-between p-6 border-b border-monday-border">
          <h3 className="font-extrabold text-lg text-monday-black capitalize">{getModalTitle()}</h3>
          <button 
            onClick={() => setShowModal(false)}
            className="p-1 text-monday-gray hover:text-monday-black hover:bg-monday-gray-background rounded-lg transition-300"
          >
            <X size={20} />
          </button>
        </div>

        <form onSubmit={handleFormSubmit} className="flex-1 overflow-y-auto p-6 space-y-5">
          {renderFormFields()}
          
          <div className="flex items-center justify-end gap-3 pt-3 border-t border-monday-border">
            <button 
              type="button"
              onClick={() => setShowModal(false)}
              className="px-5 py-2.5 bg-monday-background hover:bg-monday-border text-monday-black rounded-full font-bold text-sm transition-300"
            >
              Batal
            </button>
            <button 
              type="submit"
              className="px-5 py-2.5 bg-monday-blue text-white rounded-full font-bold text-sm hover:bg-opacity-90 transition-300"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
