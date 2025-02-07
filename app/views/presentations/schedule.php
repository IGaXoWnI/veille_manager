<?php 
$title = 'Schedule Presentation';
ob_start(); 
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Schedule New Presentation</h1>
        <p class="mt-1 text-sm text-gray-600">Assign students to present an approved subject</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/presentations/schedule" method="POST" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select id="subject_id" name="subject_id" required
                            class="mt-2 block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                        <option value="">Select a subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>">
                                <?php echo htmlspecialchars($subject['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="scheduled_date" name="scheduled_date" required
                           min="<?php echo date('Y-m-d'); ?>"
                           class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Select Students <span class="text-sm text-gray-500">(minimum 2 required)</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[480px] overflow-y-auto px-1">
                        <?php foreach ($students as $student): ?>
                            <div class="student-item relative flex items-start p-4 rounded-lg border border-gray-200 hover:border-indigo-500 transition-colors duration-200">
                                <div class="min-w-0 flex-1 text-sm">
                                    <label for="student-<?php echo $student['id']; ?>" 
                                           class="font-medium text-gray-700 select-none cursor-pointer hover:text-indigo-600">
                                        <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                    </label>
                                </div>
                                <div class="ml-3 flex items-center h-5">
                                    <input type="checkbox" 
                                           name="student_ids[]" 
                                           value="<?php echo $student['id']; ?>"
                                           id="student-<?php echo $student['id']; ?>"
                                           class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-gray-300 rounded cursor-pointer">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="<?php echo BASE_URL; ?>/calendar" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-200 hover:shadow-md">
                    Schedule Presentation
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="student_ids[]"]');
    const form = document.querySelector('form');

    // Validation for minimum 2 students
    form.addEventListener('submit', function(e) {
        const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        if (selectedCount < 2) {
            e.preventDefault();
            alert('Please select at least 2 students');
        }
    });

    // Checkbox animation
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const container = this.closest('.student-item');
            if (this.checked) {
                container.classList.add('border-indigo-500', 'bg-indigo-50');
            } else {
                container.classList.remove('border-indigo-500', 'bg-indigo-50');
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 