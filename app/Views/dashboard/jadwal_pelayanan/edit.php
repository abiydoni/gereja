<?= $this->extend('layouts/backend') ?>

<?= $this->section('styles') ?>
<!-- Add specific styles if needed -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 font-heading">Edit Jadwal Ibadah</h1>
            <p class="text-slate-500 text-sm mt-1">Perbarui jadwal dan petugas pelayanan.</p>
        </div>
        <a href="<?= base_url('dashboard/jadwal_pelayanan') ?>" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
            <ion-icon name="arrow-back-outline"></ion-icon>
            <span>Kembali</span>
        </a>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
    <div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center gap-3 shadow-sm">
        <ion-icon name="alert-circle" class="text-xl"></ion-icon>
        <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('dashboard/jadwal_pelayanan/update/'.$id) ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Global Header Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-6 mb-6">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">1</div>
                Pengaturan Umum (Satu Hari)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-700" required value="<?= $tanggal ?>">
                    <p class="text-[10px] text-slate-400 mt-1">Mengubah tanggal akan memindahkan semua jadwal ini.</p>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none text-sm font-medium text-slate-600">
                            <option value="aktif" <?= $status == 'aktif' ? 'selected' : '' ?>>Aktif (Akan Datang)</option>
                            <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $status == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                        <ion-icon name="chevron-down-outline" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></ion-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrix Input Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 p-4 md:p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-4 mb-6 gap-4">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center text-xs font-bold">2</div>
                    Jadwal Petugas (Matriks 3 Sesi)
                </h2>
                <!-- Global Add Button Removed -->
            </div>

            <!-- Session Configuration -->
            <div class="mb-6 px-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="flex justify-between items-center mb-3 border-b border-slate-200 pb-2">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Konfigurasi Sesi</span>
                    <button type="button" id="add-session-btn" class="px-3 py-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-bold rounded-lg text-[10px] transition-colors flex items-center gap-1">
                        <ion-icon name="add-circle"></ion-icon> Tambah Sesi
                    </button>
                </div>
                
                <div id="session-config-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Dynamic Session Inputs Here -->
                </div>
            </div>

            <!-- Column Labels & Matrix Container -->
            <div id="matrix-container-wrapper" class="overflow-x-auto pb-2">
                <div class="min-w-[800px]"> <!-- Ensure horizontal scroll on mobile if many cols -->
                    <!-- Dynamic Header -->
                    <div id="matrix-header" class="grid grid-cols-12 gap-2 mb-1 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider items-center">
                        <div class="col-span-3">Jenis Tugas</div>
                        <!-- Dynamic Session Headers Here -->
                    </div>

                    <div id="petugas-container" class="space-y-0">
                        <!-- Rows generated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Action -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                <ion-icon name="save-outline" class="text-xl"></ion-icon>
                <span>SIMPAN PERUBAHAN</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('petugas-container');
        const sessionConfigList = document.getElementById('session-config-list');
        const matrixHeader = document.getElementById('matrix-header');
        const addSessionBtn = document.getElementById('add-session-btn');

        // Data from Controller
        const serverSessionsData = <?= json_encode($sessionsData) ?>; // Keyed by ID { id: { name, time, details: [] } }
        const existingRoles = <?= json_encode($existingRoles) ?>;
        const colors = ['blue', 'orange', 'indigo', 'emerald', 'rose', 'cyan', 'amber', 'violet'];

        // Initialize sessions array from server data
        // We convert the object-based server data to our array-based UI state
        let sessions = [];
        let colorIdx = 0;
        
        // serverSessionsData is keyed by ID.
        for (const [id, data] of Object.entries(serverSessionsData)) {
            sessions.push({
                id: id, // Real DB ID (numeric)
                name: data.name,
                time: data.time,
                color: colors[colorIdx % colors.length],
                // We keep details here to help with initial lookup
                originalDetails: data.details 
            });
            colorIdx++;
        }
        
        // Logic to render UI
        function renderStructure() {
            // 1. Session Config
            sessionConfigList.innerHTML = '';
            sessions.forEach((sess, index) => {
                const div = document.createElement('div');
                div.className = `flex flex-col gap-1 relative group p-2 rounded-lg border border-${sess.color}-100 bg-${sess.color}-50/30`;
                div.innerHTML = `
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-bold text-${sess.color}-600 uppercase">Sesi ${index + 1}</span>
                        ${sessions.length > 1 ? `
                        <button type="button" class="remove-session-btn text-rose-400 hover:text-rose-600" data-id="${sess.id}" title="Hapus Sesi">
                            <ion-icon name="close-circle" class="text-lg"></ion-icon>
                        </button>` : ''}
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="sessions[${sess.id}][name]" value="${sess.name}" class="w-full px-2 py-1 text-xs border border-slate-200 rounded focus:ring-1 focus:ring-${sess.color}-400 outline-none" placeholder="Nama Ibadah" required>
                        <input type="time" name="sessions[${sess.id}][time]" value="${sess.time}" class="w-full px-2 py-1 text-xs border border-slate-200 rounded focus:ring-1 focus:ring-${sess.color}-400 outline-none" required>
                    </div>
                `;
                sessionConfigList.appendChild(div);
            });

            // 2. Matrix Header
            const gridStyle = `grid-template-columns: 200px repeat(${sessions.length}, minmax(180px, 1fr));`;
            matrixHeader.style = `display: grid; gap: 0.5rem; ${gridStyle}`;
            matrixHeader.innerHTML = `<div class="font-bold text-slate-600">JENIS TUGAS</div>`;
            
            sessions.forEach(sess => {
                const div = document.createElement('div');
                div.className = `text-center bg-${sess.color}-50 border-b-2 border-${sess.color}-200 p-1 rounded-t-lg text-${sess.color}-700 font-bold text-[10px] uppercase truncate`;
                div.textContent = `Petugas ${sess.name}`;
                matrixHeader.appendChild(div);
            });

            // Update existing rows style
            document.querySelectorAll('.petugas-row > .grid-container').forEach(rowGrid => {
                rowGrid.style = `display: grid; gap: 0.5rem; ${gridStyle} align-items: start;`;
            });
        }

        renderStructure();

        // Add Session
        addSessionBtn.addEventListener('click', () => {
             // Use string ID for new sessions to distinguish from DB IDs
            const id = 'sess_' + Date.now();
            const color = colors[sessions.length % colors.length];
            sessions.push({ id, name: `Ibadah Sesi ${sessions.length + 1}`, time: '09:00', color, originalDetails: [] });
            renderStructure();
            
            // Add cells to existing rows
            document.querySelectorAll('.petugas-row').forEach(row => {
                const grid = row.querySelector('.grid-container');
                const cell = createCell(id, '', color); // Empty value for new session
                grid.appendChild(cell);
            });
        });

        // Remove Session
        sessionConfigList.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-session-btn');
            if (btn) {
                if(!confirm('Hapus sesi ini beserta datanya?')) return;
                const id = btn.dataset.id;
                sessions = sessions.filter(s => s.id !== id); // Type loose check if needed, but strings/strs fine
                renderStructure();
                document.querySelectorAll(`.cell-${id}`).forEach(cell => cell.remove());
            }
        });

        const defaultRoles = [
            'Tema',
            'Pengkotbah',
            'Imam',
            'Warta',
            'Persembahan',
            'Kolektan',
            'Organis',
            'Singer',
            'Pujian',
            'Operator LCD',
            'Sambut Jemaat & Kolektan',
            'Bunga Mimbar'
        ];

        // Helper to find initial value for a cell
        function findPerson(sess, roleName) {
            if (!sess.originalDetails) return '';
            const detail = sess.originalDetails.find(d => d.jenis_tugas === roleName);
            return detail ? detail.nama_petugas : '';
        }

        function createCell(sessId, val = '', color = 'slate') {
            const div = document.createElement('div');
            div.className = `col-span-1 cell-${sessId}`;
            div.innerHTML = `
                <label class="md:hidden block text-[10px] font-bold text-${color}-400 uppercase tracking-wider mb-1">Sesi ${sessId}</label>
                <textarea name="petugas[${sessId}][]" rows="2" class="w-full px-2 py-1 bg-white border border-${color}-100 rounded focus:ring-1 focus:ring-${color}-100 outline-none transition-all text-xs min-h-[50px] placeholder-slate-300 resize-y" placeholder="-">${val}</textarea>
            `;
            return div;
        }

        function createRow(roleValue = '', insertAfterNode = null) {
            const row = document.createElement('div');
            row.className = 'petugas-row relative bg-white border-b border-slate-50 pb-1 mb-1 md:mb-0 md:pb-0 md:border-b-0 animate-fade-in group';
            
            // Dynamic Grid
            const gridStyle = `display: grid; gap: 0.5rem; grid-template-columns: 200px repeat(${sessions.length}, minmax(180px, 1fr)); align-items: start;`;

            const grid = document.createElement('div');
            grid.className = 'grid-container';
            grid.style = gridStyle;

            // 1. Task Column
            const taskCol = document.createElement('div');
            taskCol.innerHTML = `
                <div class="flex gap-1">
                    <div class="flex flex-col gap-0.5 shrink-0">
                        <button type="button" class="add-row-btn w-6 h-6 rounded bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition-colors shadow-sm border border-emerald-100" title="Tambah Baris">
                            <ion-icon name="add" class="text-xs"></ion-icon>
                        </button>
                        <button type="button" class="remove-row w-6 h-6 rounded bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors shadow-sm border border-rose-100" title="Hapus Baris">
                            <ion-icon name="trash-outline" class="text-xs"></ion-icon>
                        </button>
                    </div>
                    <input type="text" name="jenis_tugas[]" class="w-full px-2 py-1 bg-slate-50 border border-slate-200 rounded text-xs font-bold text-slate-700 focus:bg-white focus:ring-1 focus:ring-slate-200 outline-none transition-all h-[50px]" placeholder="Tugas" value="${roleValue}">
                </div>
            `;
            grid.appendChild(taskCol);

            // 2. Session Columns
            sessions.forEach(sess => {
                // Find value for this cell if roleValue exists
                let val = '';
                if (roleValue) {
                    val = findPerson(sess, roleValue);
                }
                grid.appendChild(createCell(sess.id, val, sess.color));
            });

            row.appendChild(grid);

            if (insertAfterNode && insertAfterNode.parentNode === container) {
                insertAfterNode.after(row);
            } else {
                container.appendChild(row);
            }
        }

        // Initialize Rows from Existing Roles
        if (existingRoles && existingRoles.length > 0) {
            existingRoles.forEach(role => createRow(role));
        } else {
            defaultRoles.forEach(role => createRow(role));
        }

        // Event Delegation
        container.addEventListener('click', function(e) {
            const addBtn = e.target.closest('.add-row-btn');
            if (addBtn) {
                const currentRow = addBtn.closest('.petugas-row');
                createRow('', currentRow);
            }

            const removeBtn = e.target.closest('.remove-row');
            if (removeBtn) {
                const row = removeBtn.closest('.petugas-row');
                row.remove();
                if(container.children.length === 0) createRow();
            }
        });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>

<?= $this->endSection() ?>
