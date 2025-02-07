<?php 
$title = 'Calendar';
ob_start(); 

$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

$firstDay = mktime(0, 0, 0, $month, 1, $year);
$numberDays = date('t', $firstDay);
$dateComponents = getdate($firstDay);
$monthName = $dateComponents['month'];
$dayOfWeek = $dateComponents['wday'];
$currentDay = date('Y-m-d');
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Calendar</h1>
                <p class="mt-1 text-lg text-gray-600">
                    <?php echo $monthName . " " . $year; ?>
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <?php
                $prevMonth = $month - 1;
                $prevYear = $year;
                if ($prevMonth == 0) {
                    $prevMonth = 12;
                    $prevYear--;
                }
                
                $nextMonth = $month + 1;
                $nextYear = $year;
                if ($nextMonth == 13) {
                    $nextMonth = 1;
                    $nextYear++;
                }
                ?>
                <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" 
                   class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <a href="?month=<?php echo date('m'); ?>&year=<?php echo date('Y'); ?>" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Today
                </a>
                <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" 
                   class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="grid grid-cols-7 gap-px border-b border-gray-200">
            <?php
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $day) {
                echo '<div class="px-4 py-3 text-sm font-semibold text-gray-900 bg-gray-50">' . $day . '</div>';
            }
            ?>
        </div>

        <div class="grid grid-cols-7 gap-px">
            <?php
            for ($i = 0; $i < $dayOfWeek; $i++) {
                echo '<div class="min-h-[120px] bg-gray-50 p-2 relative"></div>';
            }

            for ($day = 1; $day <= $numberDays; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $hasEvents = isset($groupedPresentations[$currentDate]);
                $isToday = $currentDate === $currentDay;
                
                $cellClass = 'min-h-[120px] bg-white p-2 relative hover:bg-gray-50 transition-colors duration-200';
                if ($isToday) {
                    $cellClass .= ' ring-2 ring-indigo-600 ring-inset';
                }
                
                echo '<div class="' . $cellClass . '">';
                echo '<div class="flex items-center justify-between">';
                echo '<span class="' . ($isToday ? 'font-bold text-indigo-600' : 'font-medium text-gray-900') . '">' . $day . '</span>';
                if ($hasEvents) {
                    echo '<span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>';
                }
                echo '</div>';
                
                if ($hasEvents) {
                    echo '<div class="mt-2 space-y-1">';
                    foreach ($groupedPresentations[$currentDate] as $presentation) {
                        echo '<div class="group relative">';
                        echo '<div class="px-2 py-1 text-xs bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition-colors duration-200 cursor-pointer">';
                        echo '<div class="font-medium">' . date('H:i', strtotime($presentation['scheduled_date'])) . '</div>';
                        echo '<div class="truncate">' . htmlspecialchars($presentation['subject_title']) . '</div>';
                        echo '<div class="text-xs text-indigo-500 truncate">' . htmlspecialchars($presentation['student_names']) . '</div>';
                        echo '</div>';
                        
                        echo '<div class="hidden group-hover:block absolute left-full top-0 ml-2 w-64 p-3 bg-white rounded-lg shadow-lg border border-gray-200 z-10">';
                        echo '<div class="text-sm font-medium text-gray-900">' . htmlspecialchars($presentation['subject_title']) . '</div>';
                        echo '<div class="mt-1 text-xs text-gray-600">' . date('H:i', strtotime($presentation['scheduled_date'])) . '</div>';
                        echo '<div class="mt-2 text-xs text-gray-700">';
                        echo '<span class="font-medium">Presenters:</span><br>';
                        echo htmlspecialchars($presentation['student_names']);
                        echo '</div>';
                        if (!empty($presentation['subject_description'])) {
                            echo '<div class="mt-2 text-xs text-gray-600">';
                            echo '<span class="font-medium">Description:</span><br>';
                            echo htmlspecialchars($presentation['subject_description']);
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }

            $remainingDays = 7 - (($dayOfWeek + $numberDays) % 7);
            if ($remainingDays < 7) {
                for ($i = 0; $i < $remainingDays; $i++) {
                    echo '<div class="min-h-[120px] bg-gray-50 p-2 relative"></div>';
                }
            }
            ?>
        </div>
    </div>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teacher'): ?>
        <div class="mt-6 flex justify-end">
            <a href="<?php echo BASE_URL; ?>/presentations/schedule" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform transition hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Schedule Presentation
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 