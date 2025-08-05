<?= $this->include('layouts/base.php', [
    'page_title' => $page_title,
    'content' => function() use ($services, $menu_items) {
?>
<div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg">
        <div class="p-4 border-b">
            <h1 class="text-xl font-bold text-gray-800">DAIMON Hub</h1>
        </div>
        
        <nav class="mt-4">
            <?php foreach ($menu_items as $item): ?>
                <?php if (isset($item['children']) && count($item['children']) > 0): ?>
                    <div class="px-4 py-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <?= $this->e($item['title']) ?>
                    </div>
                    <?php foreach ($item['children'] as $child): ?>
                        <a href="#" class="block px-6 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <?= $this->e($child['title']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="#" class="block px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        <?= $this->e($item['title']) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </div>

    <!-- Main content -->
    <div class="ml-64 p-8">
        <header class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800"><?= $this->e($page_title) ?></h1>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $service): ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-cube text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900"><?= $this->e($service->getName()) ?></h3>
                                <p class="text-sm text-gray-500"><?= $this->e($service->getDescription()) ?></p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                v<?= $this->e($service->getVersion()) ?>
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                <?= $service->isEnabled() ? 'Attivo' : 'Disattivato' ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php 
    }
) ?>
