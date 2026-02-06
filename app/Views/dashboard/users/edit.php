<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit User</h3>
        </div>

        <form action="<?= base_url('dashboard/users/update/'.$user['id_user']) ?>" method="post">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $user['username'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif" <?= $user['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= $user['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru (Opsional)</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Biarkan kosong jika tidak diganti">
                <p class="text-xs text-slate-500 mt-1">Minimal 6 karakter jika ingin mengganti.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Role / Hak Akses</label>
                <select name="role" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <?php if(session()->get('role') == 'superadmin'): ?>
                    <option value="superadmin" <?= $user['role'] == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                    <?php endif; ?>
                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User / Pengurus</option>
                    <option value="keuangan" <?= $user['role'] == 'keuangan' ? 'selected' : '' ?>>Bendahara / Keuangan</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/users') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Perbarui User</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
