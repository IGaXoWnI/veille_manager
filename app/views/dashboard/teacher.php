<?php 
$title = 'Teacher Dashboard';
ob_start(); 
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="mb-8 p-6">
        <h1 class="text-3xl font-bold text-gray-800">Welcome back, <?php echo isset($_SESSION['first_name']) && isset($_SESSION['last_name']) ? 
            htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : 
            'Teacher'; ?></h1>
        <p class="text-gray-600 mt-2">Manage your teaching activities and student presentations</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Subjects</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">
                        <?php echo count($pendingSubjects); ?>
                    </p>
                </div>
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Approved: <?php echo $approvedSubjectsCount; ?></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Presentations</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">
                        <?php echo $todayPresentations; ?>
                    </p>
                </div>
                <div class="bg-emerald-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Upcoming: <?php echo $upcomingPresentations; ?></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                    <p class="text-2xl font-bold text-violet-600 mt-1">
                        <?php echo $totalStudents; ?>
                    </p>
                </div>
                <div class="bg-violet-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Presentations</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        <?php echo $totalPresentations; ?>
                    </p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Active: <?php echo $upcomingPresentations; ?></p>
        </div>
    </div>

    <div class="px-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="<?php echo BASE_URL; ?>/subjects/manage" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-50 p-3 rounded-lg group-hover:bg-indigo-100 transition-all">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Manage Subjects</h3>
                        <p class="text-sm text-gray-500">Review and approve subject suggestions</p>
                    </div>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/calendar" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center space-x-4">
                    <div class="bg-emerald-50 p-3 rounded-lg group-hover:bg-emerald-100 transition-all">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Calendar</h3>
                        <p class="text-sm text-gray-500">View upcoming presentations</p>
                    </div>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/presentations/schedule" 
               class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center space-x-4">
                    <div class="bg-violet-50 p-3 rounded-lg group-hover:bg-violet-100 transition-all">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Schedule Presentation</h3>
                        <p class="text-sm text-gray-500">Create new presentation slots</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="px-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Subject Suggestions</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <?php if (empty($pendingSubjects)): ?>
                <div class="p-6 text-center">
                    <p class="text-gray-500">No pending subjects to review</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($pendingSubjects as $subject): ?>
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($subject['title']); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($subject['description']); ?></p>
                                    <p class="text-xs text-gray-400">Suggested by: <?php echo htmlspecialchars($subject['first_name'] . ' ' . $subject['last_name']); ?></p>
                                </div>
                                <div class="flex space-x-2">
                                    <form method="POST" action="<?php echo BASE_URL; ?>/subjects/approve">
                                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                        <button type="submit" 
                                                class="px-3 py-1.5 text-sm font-medium text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 focus:ring-2 focus:ring-emerald-300 transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/subjects/reject">
                                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                        <button type="submit" 
                                                class="px-3 py-1.5 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-300 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 