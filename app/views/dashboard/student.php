<?php 
$title = 'Student Dashboard';
ob_start(); 
?>

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-lg font-medium mb-4">Your Upcoming Presentations</h2>
        
        <?php if (!empty($upcoming_presentations)): ?>
            <div class="space-y-4">
                <?php foreach ($upcoming_presentations as $presentation): ?>
                    <div class="border rounded p-4">
                        <h3 class="font-medium"><?php echo htmlspecialchars($presentation['subject_title']); ?></h3>
                        <p class="text-gray-600">
                            Date: <?php echo date('F j, Y', strtotime($presentation['scheduled_date'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">No upcoming presentations.</p>
        <?php endif; ?>
    </div>
</div>

<div class="mt-6 bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-lg font-medium mb-4">Your Suggested Subjects</h2>
        
        <?php if (!empty($suggested_subjects)): ?>
            <div class="space-y-4">
                <?php foreach ($suggested_subjects as $subject): ?>
                    <div class="border rounded p-4">
                        <h3 class="font-medium"><?php echo htmlspecialchars($subject['title']); ?></h3>
                        <p class="text-gray-600">
                            Status: <span class="font-medium"><?php echo ucfirst($subject['status']); ?></span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">No suggested subjects.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 