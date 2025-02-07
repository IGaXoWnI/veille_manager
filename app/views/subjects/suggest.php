<?php 
$title = 'Suggest Subject';
ob_start(); 
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-6">Suggest a Subject</h2>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/subjects/suggest">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/app/views/templates/dashboard_layout.php';
?> 