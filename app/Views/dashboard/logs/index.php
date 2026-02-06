<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-end gap-4" data-aos="fade-down">
    <div>
        <h1 class="font-heading font-extrabold text-2xl text-slate-800">Log Aktivitas</h1>
        <p class="text-slate-500">Rekam jejak aktivitas pengguna dalam sistem.</p>
    </div>
    
    <form action="" method="get" class="flex gap-2">
        <div class="relative">
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari user, aksi, data..." 
                   class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary w-64 text-sm transition-all shadow-sm">
            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></ion-icon>
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-lg shadow-primary/20">
            Cari
        </button>
    </form>
</div>

<div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden" data-aos="fade-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="p-2 font-bold text-slate-600 text-xs uppercase tracking-wider">Waktu</th>
                    <th class="p-2 font-bold text-slate-600 text-xs uppercase tracking-wider">User</th>
                    <th class="p-2 font-bold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    <th class="p-2 font-bold text-slate-600 text-xs uppercase tracking-wider">Target</th>
                    <th class="p-2 font-bold text-slate-600 text-xs uppercase tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($logs as $index => $log): ?>
                <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="openLogModal(<?= $index ?>)">
                    <td class="px-2 py-1 text-slate-500 text-xs whitespace-nowrap border-b border-slate-50">
                        <?= date('d/m/y H:i', strtotime($log['created_at'])) ?>
                        <span class="text-[10px] text-slate-400 ml-1">(<?= $log['ip_address'] ?>)</span>
                    </td>
                    <td class="px-2 py-1 border-b border-slate-50">
                        <span class="font-bold text-slate-700 text-xs"><?= $log['username'] ?></span>
                        <span class="text-[10px] text-slate-400 ml-1">#<?= $log['user_id'] ?></span>
                    </td>
                    <td class="px-2 py-1 border-b border-slate-50">
                        <?php
                            $color = 'slate';
                            if ($log['action'] == 'CREATE') $color = 'emerald';
                            if ($log['action'] == 'UPDATE') $color = 'amber';
                            if ($log['action'] == 'DELETE') $color = 'red';
                            if ($log['action'] == 'LOGIN') $color = 'blue';
                            if ($log['action'] == 'LOGOUT') $color = 'slate';
                        ?>
                        <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-bold bg-<?= $color ?>-100 text-<?= $color ?>-800 uppercase">
                            <?= $log['action'] ?>
                        </span>
                    </td>
                    <td class="px-2 py-1 text-xs text-slate-600 border-b border-slate-50">
                        <span class="font-medium"><?= $log['table_name'] ?></span>
                        <span class="text-[10px] text-slate-400 ml-1">#<?= $log['record_id'] ?></span>
                    </td>
                    <td class="px-2 py-1 text-[10px] font-mono text-slate-500 max-w-md border-b border-slate-50 leading-none truncate">
                        Klik untuk detail
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($logs)): ?>
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400">Belum ada data aktivitas.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-100">
        <?= $pager->links('logs', 'default_full') ?>
    </div>
</div>

<!-- Modal Detail -->
<div id="logModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeLogModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4" id="modal-title">
                            Detail Perubahan
                        </h3>
                        <div class="mt-2">
                             <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                <div>
                                    <span class="text-slate-500">User:</span>
                                    <span id="modalUser" class="font-bold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500">Waktu:</span>
                                    <span id="modalTime" class="font-bold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500">Aksi:</span>
                                    <span id="modalAction" class="font-bold text-slate-700"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500">Target:</span>
                                    <span id="modalTarget" class="font-bold text-slate-700"></span>
                                </div>
                             </div>

                             <div class="overflow-x-auto border rounded-lg">
                                 <table class="w-full text-sm text-left">
                                     <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs border-b">
                                         <tr>
                                             <th class="px-4 py-2 w-1/4">Field</th>
                                             <th class="px-4 py-2 w-1/3 text-red-600 bg-red-50">Data Lama</th>
                                             <th class="px-4 py-2 w-1/3 text-emerald-600 bg-emerald-50">Data Baru</th>
                                         </tr>
                                     </thead>
                                     <tbody id="modalChangesBody" class="divide-y divide-slate-100">
                                         <!-- JS will populate this -->
                                     </tbody>
                                 </table>
                             </div>
                             
                             <div id="modalRawJson" class="mt-4 p-2 bg-slate-50 rounded text-[10px] font-mono whitespace-pre-wrap text-slate-500 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeLogModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Embed log data safely
    const logsData = <?= json_encode($logs) ?>;

    function openLogModal(index) {
        const log = logsData[index];
        if(!log) return;

        // Populate Header Info
        document.getElementById('modalUser').innerText = log.username + ' (#' + log.user_id + ')';
        document.getElementById('modalTime').innerText = log.created_at; // Format nicely if needed
        document.getElementById('modalAction').innerText = log.action;
        document.getElementById('modalTarget').innerText = log.table_name + ' #' + log.record_id;

        // Parse JSON
        let oldVal = {};
        let newVal = {};
        
        try {
            if(log.old_values) oldVal = JSON.parse(log.old_values);
        } catch(e) { oldVal = { error: log.old_values }; }

        try {
            if(log.new_values) newVal = JSON.parse(log.new_values);
        } catch(e) { newVal = { error: log.new_values }; }

        // Combine keys
        const allKeys = new Set([...Object.keys(oldVal || {}), ...Object.keys(newVal || {})]);
        const tbody = document.getElementById('modalChangesBody');
        tbody.innerHTML = '';

        if(allKeys.size === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-slate-400">Tidak ada data detail yang tersimpan.</td></tr>';
        } else {
            allKeys.forEach(key => {
                const tr = document.createElement('tr');
                const vOld = oldVal && oldVal[key] !== undefined ? oldVal[key] : '-';
                const vNew = newVal && newVal[key] !== undefined ? newVal[key] : '-';
                
                // Highlight diff
                const isDifferent = JSON.stringify(vOld) !== JSON.stringify(vNew);
                const bgClass = isDifferent ? 'bg-yellow-50' : '';

                tr.className = `hover:bg-slate-50 ${bgClass}`;
                tr.innerHTML = `
                    <td class="px-4 py-2 font-medium text-slate-600 border-r border-slate-100 break-all">${key}</td>
                    <td class="px-4 py-2 text-slate-500 border-r border-slate-100 break-all font-mono text-xs">${escapeHtml(vOld)}</td>
                    <td class="px-4 py-2 text-slate-800 break-all font-mono text-xs">${escapeHtml(vNew)}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('logModal').classList.remove('hidden');
    }

    function closeLogModal() {
        document.getElementById('logModal').classList.add('hidden');
    }

    function escapeHtml(text) {
        if(text === null) return 'null';
        if(typeof text === 'object') return JSON.stringify(text);
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>

<?= $this->endSection() ?>
