<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jam = trim($_POST['jam'] ?? '');
    $tema = trim($_POST['tema'] ?? '');
    $pengkotbah = trim($_POST['pengkotbah'] ?? '');
    $bacaan = trim($_POST['bacaan'] ?? '');
    $imam = trim($_POST['imam'] ?? '');
    $baca_warta = trim($_POST['baca_warta'] ?? '');
    $sahadat = trim($_POST['sahadat'] ?? '');
    $persembahan = trim($_POST['persembahan'] ?? '');
    $musik = trim($_POST['musik'] ?? '');
    $pnj = trim($_POST['pnj'] ?? '');
    $lcd = trim($_POST['lcd'] ?? '');
    $pengisi = trim($_POST['pengisi'] ?? '');
    $dekorasi = trim($_POST['dekorasi'] ?? '');
    $lain = trim($_POST['lain'] ?? '');

    // Helper untuk sanitasi CSV NAMA jemaat: pisahkan koma, trim, buang entri kosong
    $sanitizeCsvNames = function(string $value): string {
        if ($value === '') { return ''; }
        $parts = array_map('trim', explode(',', $value));
        $clean = [];
        foreach ($parts as $p) {
            if ($p !== '') { $clean[] = $p; }
        }
        return implode(',', $clean);
    };

    // Bersihkan field yang berisi daftar nama jemaat (kecuali bacaan yang adalah ayat Alkitab)
    $pengkotbah = $sanitizeCsvNames($pengkotbah);
    $imam = $sanitizeCsvNames($imam);
    $baca_warta = $sanitizeCsvNames($baca_warta);
    $sahadat = $sanitizeCsvNames($sahadat);
    $persembahan = $sanitizeCsvNames($persembahan);
    $musik = $sanitizeCsvNames($musik);
    $pnj = $sanitizeCsvNames($pnj);
    $lcd = $sanitizeCsvNames($lcd);
    $pengisi = $sanitizeCsvNames($pengisi);
    $dekorasi = $sanitizeCsvNames($dekorasi);
    $lain = $sanitizeCsvNames($lain);

    if ($tanggal === '' || $jam === '' || $tema === '') {
        $error = 'Tanggal, Jam, dan Tema wajib diisi.';
    } else {
        try {
            $db = new Database();
            $sql = "INSERT INTO jadwal (tanggal, tema, jam, pengkotbah, bacaan, imam, baca_warta, sahadat, persembahan, musik, pnj, lcd, pengisi, dekorasi, lain, time)
                    VALUES (:tanggal, :tema, :jam, :pengkotbah, :bacaan, :imam, :baca_warta, :sahadat, :persembahan, :musik, :pnj, :lcd, :pengisi, :dekorasi, :lain, NOW())";
            $db->query($sql);
            $db->bind(':tanggal', $tanggal . ' ' . $jam);
            $db->bind(':tema', $tema);
            $db->bind(':jam', $jam);
            $db->bind(':pengkotbah', $pengkotbah);
            $db->bind(':bacaan', $bacaan);
            $db->bind(':imam', $imam);
            $db->bind(':baca_warta', $baca_warta);
            $db->bind(':sahadat', $sahadat);
            $db->bind(':persembahan', $persembahan);
            $db->bind(':musik', $musik);
            $db->bind(':pnj', $pnj);
            $db->bind(':lcd', $lcd);
            $db->bind(':pengisi', $pengisi);
            $db->bind(':dekorasi', $dekorasi);
            $db->bind(':lain', $lain);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/jadwal_pelayanan/?success=1');
            exit;
        } catch (Exception $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}
?>
    <div class="max-w-full mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Jadwal Pelayanan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jadwal_pelayanan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Jam</label>
                        <input type="time" name="jam" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tema</label>
                        <textarea name="tema" class="form-input" rows="3" required></textarea>
                    </div>
                </div>

                <div>
                    <label class="form-label">Bacaan</label>
                    <input name="bacaan" class="form-input" placeholder="Contoh: Yohanes 3:16-18">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Pengkotbah</label>
                        <div id="wrap_pengkotbah" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="pengkotbah" name="pengkotbah" class="form-input hidden">
                                <input id="pengkotbah_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('pengkotbah')">+</button>
                            </div>
                            <div id="list_pengkotbah" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Tambah nama satu per satu dengan tombol +. Sistem menyimpan sebagai CSV nama.</p>
                    </div>
                    <div>
                        <label class="form-label">Imam</label>
                        <div id="wrap_imam" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="imam" name="imam" class="form-input hidden">
                                <input id="imam_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('imam')">+</button>
                            </div>
                            <div id="list_imam" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Baca Warta</label>
                        <div id="wrap_baca_warta" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="baca_warta" name="baca_warta" class="form-input hidden">
                                <input id="baca_warta_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('baca_warta')">+</button>
                            </div>
                            <div id="list_baca_warta" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Sahadat</label>
                        <div id="wrap_sahadat" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="sahadat" name="sahadat" class="form-input hidden">
                                <input id="sahadat_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('sahadat')">+</button>
                            </div>
                            <div id="list_sahadat" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Persembahan</label>
                        <div id="wrap_persembahan" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="persembahan" name="persembahan" class="form-input hidden">
                                <input id="persembahan_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('persembahan')">+</button>
                            </div>
                            <div id="list_persembahan" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Musik</label>
                        <div id="wrap_musik" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="musik" name="musik" class="form-input hidden">
                                <input id="musik_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('musik')">+</button>
                            </div>
                            <div id="list_musik" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Penanggung Jawab (PNJ)</label>
                        <div id="wrap_pnj" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="pnj" name="pnj" class="form-input hidden">
                                <input id="pnj_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('pnj')">+</button>
                            </div>
                            <div id="list_pnj" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">LCD</label>
                        <div id="wrap_lcd" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="lcd" name="lcd" class="form-input hidden">
                                <input id="lcd_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('lcd')">+</button>
                            </div>
                            <div id="list_lcd" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Pengisi Liturgi</label>
                        <div id="wrap_pengisi" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="pengisi" name="pengisi" class="form-input hidden">
                                <input id="pengisi_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('pengisi')">+</button>
                            </div>
                            <div id="list_pengisi" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Dekorasi</label>
                        <div id="wrap_dekorasi" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="dekorasi" name="dekorasi" class="form-input hidden">
                                <input id="dekorasi_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('dekorasi')">+</button>
                            </div>
                            <div id="list_dekorasi" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Lain-lain</label>
                        <div id="wrap_lain" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="lain" name="lain" class="form-input hidden">
                                <input id="lain_new" type="text" class="form-input" placeholder="Nama jemaat">
                                <button type="button" class="btn-secondary" onclick="appendName('lain')">+</button>
                            </div>
                            <div id="list_lain" class="grid grid-cols-1 md:grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


<script>
function appendName(field) {
    const hidden = document.getElementById(field);
    const add = document.getElementById(field + '_new');
    const list = document.getElementById('list_' + field);
    if (!hidden || !add || !list) return;
    const val = (add.value || '').trim();
    if (val === '') return;
    // Update hidden CSV and re-render
    if (hidden.value.trim() === '') hidden.value = val;
    else hidden.value = hidden.value.replace(/\s*,\s*/g, ',') + ',' + val;
    renderList(field);
    add.value = '';
    add.focus();
}

function parseCsv(str) {
    if (!str) return [];
    return str.split(',').map(s => s.trim()).filter(Boolean);
}

function renderList(field) {
    const hidden = document.getElementById(field);
    const list = document.getElementById('list_' + field);
    if (!hidden || !list) return;
    list.innerHTML = '';
    const items = parseCsv(hidden.value);
    items.forEach((name, idx) => {
        const wrap = document.createElement('div');
        wrap.className = 'relative inline-block mr-2 mb-2';
        const badge = document.createElement('div');
        badge.className = 'px-2 py-1 pr-5 rounded bg-amber-50 text-amber-800 text-xs';
        badge.textContent = (idx + 1) + '. ' + name;
        badge.title = 'Klik dua kali untuk edit';
        badge.ondblclick = function(){ editName(field, idx); };
        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'absolute -top-1 -right-1 w-5 h-5 rounded-full text-red-600 hover:text-red-700 text-[10px] leading-5 text-center';
        close.textContent = '×';
        close.onclick = function(){ removeName(field, idx); };
        wrap.appendChild(badge);
        wrap.appendChild(close);
        list.appendChild(wrap);
    });
}

function removeName(field, index) {
    const hidden = document.getElementById(field);
    if (!hidden) return;
    const items = parseCsv(hidden.value);
    items.splice(index, 1);
    hidden.value = items.join(',');
    renderList(field);
}

function editName(field, index) {
    const hidden = document.getElementById(field);
    if (!hidden) return;
    const items = parseCsv(hidden.value);
    const current = items[index] || '';
    const updated = prompt('Ubah nama', current);
    if (updated === null) return;
    const val = updated.trim();
    if (val === '') return;
    items[index] = val;
    hidden.value = items.join(',');
    renderList(field);
}

// Inisialisasi awal (jaga-jaga jika ada nilai default)
document.addEventListener('DOMContentLoaded', function(){
    ['pengkotbah','imam','baca_warta','sahadat','persembahan','musik','pnj','lcd','pengisi','dekorasi','lain']
        .forEach(renderList);
});
</script>
