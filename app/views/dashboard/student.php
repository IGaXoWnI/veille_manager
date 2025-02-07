<?php 
$title = 'Student Dashboard';
ob_start(); 
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Banner -->
        <div class="mb-8 bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-8 shadow-xl">
            <h1 class="text-3xl font-bold text-white mb-2">
                Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>
            </h1>
            <p class="text-indigo-100">Your learning journey continues here</p>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-xl p-6 transform transition-all hover:scale-105 duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Total Presentations</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_presentations']; ?></h3>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 transform transition-all hover:scale-105 duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Subjects Suggested</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-2"><?php echo $stats['total_suggestions']; ?></h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 transform transition-all hover:scale-105 duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wide">Last Presentation</p>
                        <h3 class="text-xl font-bold text-gray-900 mt-2">
                            <?php echo $stats['last_presentation_date'] ? date('F j, Y', strtotime($stats['last_presentation_date'])) : 'No presentations yet'; ?>
                        </h3>
                    </div>
                    <div class="bg-emerald-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="<?php echo BASE_URL; ?>/subjects/suggest" 
               class="group bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-violet-100 p-3 rounded-full group-hover:bg-violet-200 transition-colors duration-300">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-violet-600 transition-colors duration-300">Suggest New Subject</h3>
                        <p class="text-gray-600">Propose a new topic for future presentations</p>
                    </div>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/calendar" 
               class="group bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-indigo-100 p-3 rounded-full group-hover:bg-indigo-200 transition-colors duration-300">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">View Calendar</h3>
                        <p class="text-gray-600">See all upcoming presentations</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Upcoming Presentations -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Upcoming Presentations</h2>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <?php if (empty($upcomingPresentations)): ?>
                    <div class="p-6 text-center">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">No upcoming presentations scheduled.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($upcomingPresentations as $presentation): ?>
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($presentation['subject_title']); ?></h3>
                                        <p class="text-gray-600 mt-1">
                                            <?php echo date('F j, Y', strtotime($presentation['scheduled_date'])); ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            Scheduled
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Past Presentations -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Past Presentations</h2>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <?php if (empty($pastPresentations)): ?>
                    <div class="p-6 text-center">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">No past presentations yet.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($pastPresentations as $presentation): ?>
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900"><?php echo htmlspecialchars($presentation['subject_title']); ?></h3>
                                        <p class="text-gray-600 mt-1">
                                            Presented on <?php echo date('F j, Y', strtotime($presentation['scheduled_date'])); ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                            Completed
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 