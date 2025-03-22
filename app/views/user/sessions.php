<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-6 flex flex-col">
            <div class="px-6 mb-8">
                <h2 class="text-2xl font-bold">User Panel</h2>
            </div>
            <nav class="flex-1">
                <a href="?controller=user&action=dashboard" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="?controller=user&action=sessions" class="block px-6 py-2 hover:bg-gray-700 bg-gray-700">
                    <i class="fas fa-history mr-2"></i> Session History
                </a>
                <a href="?controller=user&action=settings" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
            </nav>
            <div class="px-6 py-4">
                <div class="flex items-center mb-4">
                    <img src="/avatars/<?= $user['avatar'] ?>" class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($user['username']) ?></p>
                        <p class="text-sm text-gray-400">User Account</p>
                    </div>
                </div>
                <a href="?controller=auth&action=logout" class="block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Session History</h1>
                </div>
            </header>

            <main class="p-6">
                <!-- Current Session -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Current Session</h2>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                <i class="fas fa-desktop text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Device:</span> 
                                    <?= htmlspecialchars($currentSession['user_agent']) ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">IP Address:</span> 
                                    <?= htmlspecialchars($currentSession['ip_address']) ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Started:</span> 
                                    <?= date('F j, Y g:i A', strtotime($currentSession['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Active Now</span>
                        </div>
                    </div>
                </div>

                <!-- Session History -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-xl">Previous Sessions</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($sessions as $session): ?>
                        <?php if ($session['session_token'] !== session_id()): ?>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-gray-100">
                                        <i class="fas fa-desktop text-gray-500 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Device:</span> 
                                            <?= htmlspecialchars($session['user_agent']) ?>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">IP Address:</span> 
                                            <?= htmlspecialchars($session['ip_address']) ?>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Started:</span> 
                                            <?= date('F j, Y g:i A', strtotime($session['created_at'])) ?>
                                        </p>
                                        <?php if (!$session['is_active']): ?>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Ended:</span> 
                                            <?= date('F j, Y g:i A', strtotime($session['last_activity'])) ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <?php if ($session['is_active']): ?>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                        Active on Another Device
                                    </span>
                                    <?php else: ?>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Ended</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
