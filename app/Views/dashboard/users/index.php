<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Daftar User</h3>
        <a href="<?= base_url('dashboard/users/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Username</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Role</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Created At</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-slate-500 text-sm">Belum ada user.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($users as $u): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <?= $u['username'] ?>
                            <?php if($u['id_user'] == session()->get('id_user')): ?>
                                <span class="ml-2 px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs text-center">Me</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm">
                            <span class="capitalize px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200"><?= $u['role'] ?></span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        <td class="py-3 px-4 text-sm">
                            <?php if ($u['status'] == 'aktif'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/users/edit/'.$u['id_user']) ?>" class="p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <?php if($u['id_user'] != session()->get('id_user')): ?>
                                <a href="<?= base_url('dashboard/users/delete/'.$u['id_user']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
