<?php 
$title = 'Edit Presentation';
ob_start(); 
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Presentation</h1>
        <p class="mt-1 text-sm text-gray-600">Update presentation details and schedule</p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="<?php echo BASE_URL; ?>/presentations/update" method="POST" class="space-y-6">
            <input type="hidden" name="presentation_id" value="<?php echo $presentation['id']; ?>">
            
            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                <select id="subject_id" name="subject_id" required 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['id']; ?>" 
                                <?php echo ($subject['id'] == $presentation['subject_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date Selection -->
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="scheduled_date" name="scheduled_date" 
                       value="<?php echo date('Y-m-d', strtotime($presentation['scheduled_date'])); ?>" 
                       required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Students Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Students</label>
                <div class="mt-1 border border-gray-300 rounded-md px-3 py-2 max-h-48 overflow-y-auto">
                    <?php foreach ($students as $student): ?>
                        <label class="flex items-center space-x-3 py-2">
                            <input type="checkbox" 
                                   name="student_ids[]" 
                                   value="<?php echo $student['id']; ?>"
                                   <?php echo in_array($student['id'], $selectedStudents) ? 'checked' : ''; ?>
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700">
                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="<?php echo BASE_URL; ?>/subjects/manage" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Presentation
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 