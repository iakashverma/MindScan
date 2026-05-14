<?php
declare(strict_types=1);

$pageTitle = 'Research Content';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../partials/admin_header.php';

$errors = [];
$success = '';
$items = [];
$documents = [];

$pdo = null;
try {
    $pdo = get_db();
} catch (Throwable $e) {
    $errors[] = 'Database connection unavailable. Please verify your database setup.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $action = post_string('action');

    if ($action === 'create') {
        $title = post_string('title');
        $itemType = post_string('item_type');
        $body = post_string('body');
        $statLabel = post_string('stat_label');
        $statValue = post_string('stat_value');
        $imagePath = '';

        $validTypes = ['summary', 'stat', 'image', 'article'];

        if ($title === '') {
            $errors[] = 'Title is required.';
        }
        if (!in_array($itemType, $validTypes, true)) {
            $errors[] = 'Invalid content type selected.';
        }

        if ($itemType === 'stat') {
            if ($statLabel === '' || $statValue === '') {
                $errors[] = 'Stat label and value are required for a stat item.';
            }
        }

        if ($itemType === 'summary' || $itemType === 'article') {
            if ($body === '') {
                $errors[] = 'Summary text is required for this item type.';
            }
        }

        if ($itemType === 'image') {
            if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'An image upload is required for image items.';
            } else {
                $file = $_FILES['image_file'];
                if ($file['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Images must be 2MB or smaller.';
                } else {
                    $mime = '';
                    if (class_exists('finfo')) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file($file['tmp_name']);
                    }
                    if ($mime === '' && function_exists('mime_content_type')) {
                        $mime = mime_content_type($file['tmp_name']);
                    }
                    $allowed = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                    ];

                    if (!isset($allowed[$mime])) {
                        $errors[] = 'Only JPG, PNG, or WEBP images are allowed.';
                    } else {
                        $uploadDir = __DIR__ . '/../uploads/research';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $fileName = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
                        $targetPath = $uploadDir . '/' . $fileName;

                        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $errors[] = 'Unable to save uploaded image.';
                        } else {
                            $imagePath = 'uploads/research/' . $fileName;
                        }
                    }
                }
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare('INSERT INTO research_items (title, item_type, body, stat_label, stat_value, image_path) VALUES (:title, :item_type, :body, :stat_label, :stat_value, :image_path)');
                $stmt->execute([
                    'title' => $title,
                    'item_type' => $itemType,
                    'body' => $body,
                    'stat_label' => $statLabel,
                    'stat_value' => $statValue,
                    'image_path' => $imagePath,
                ]);
                $success = 'Research item added.';
            } catch (Throwable $e) {
                $errors[] = 'Unable to save the research item. Please ensure the research_items table exists.';
            }
        }
    }

    if ($action === 'delete') {
        $id = post_int('id');
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare('SELECT image_path FROM research_items WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $item = $stmt->fetch();

                if ($item && !empty($item['image_path'])) {
                    $path = __DIR__ . '/../' . $item['image_path'];
                    if (is_file($path)) {
                        unlink($path);
                    }
                }

                $deleteStmt = $pdo->prepare('DELETE FROM research_items WHERE id = :id');
                $deleteStmt->execute(['id' => $id]);
                $success = 'Research item deleted.';
            } catch (Throwable $e) {
                $errors[] = 'Unable to delete the item. Please verify the research_items table.';
            }
        }
    }

}

if ($pdo) {
    try {
        $items = $pdo->query('SELECT id, title, item_type, body, stat_label, stat_value, image_path, created_at FROM research_items ORDER BY created_at DESC')->fetchAll();
    } catch (Throwable $e) {
        $errors[] = 'Research content table not found. Run the latest database setup script.';
        $items = [];
    }
}

?>

<div class="glass-panel">
    <h5>Add Research Item</h5>
    <p class="muted">Upload charts or images, add quick stats, or post short summaries. Items appear on the public research dashboard automatically.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo e($success); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Content Type</label>
                <select name="item_type" class="form-select" required>
                    <option value="summary">Summary</option>
                    <option value="stat">Stat</option>
                    <option value="image">Image or Chart</option>
                    <option value="article">Article</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Stat Label (for stat type)</label>
                <input type="text" name="stat_label" class="form-control" placeholder="e.g. Avg Daily Screen Time">
            </div>
            <div class="col-md-6">
                <label class="form-label">Stat Value (for stat type)</label>
                <input type="text" name="stat_value" class="form-control" placeholder="e.g. 3.2 hrs">
            </div>
            <div class="col-md-12">
                <label class="form-label">Summary / Caption</label>
                <textarea name="body" class="form-control" rows="4" placeholder="Short description for summaries, articles, or image captions."></textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Image Upload (for image type)</label>
                <input type="file" name="image_file" class="form-control" accept="image/png,image/jpeg,image/webp">
                <small class="muted">Accepted formats: JPG, PNG, WEBP. Max size 2MB.</small>
            </div>
        </div>
        <button class="btn btn-gradient mt-3" type="submit">Add Item</button>
    </form>
</div>

<div class="glass-panel mt-4">
    <h5>Existing Items</h5>
    <?php if (empty($items)): ?>
        <p class="muted">No research items yet.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($items as $item): ?>
                <div class="col-md-6">
                    <div class="glass-card">
                        <div class="d-flex justify-content-between">
                            <strong><?php echo e($item['title']); ?></strong>
                            <span class="muted"><?php echo e(ucfirst((string)$item['item_type'])); ?></span>
                        </div>
                        <?php if (!empty($item['body'])): ?>
                            <?php
                            $preview = (string)$item['body'];
                            if (strlen($preview) > 120) {
                                $preview = substr($preview, 0, 117) . '...';
                            }
                            ?>
                            <p class="mt-2 mb-2 muted"><?php echo e($preview); ?></p>
                        <?php endif; ?>
                        <?php if ($item['item_type'] === 'stat'): ?>
                            <div class="muted"><?php echo e((string)$item['stat_label']); ?>: <?php echo e((string)$item['stat_value']); ?></div>
                        <?php endif; ?>
                        <?php if ($item['item_type'] === 'image' && !empty($item['image_path'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo e($item['image_path']); ?>" alt="<?php echo e($item['title']); ?>" style="max-width: 100%; border-radius: 12px;">
                            </div>
                        <?php endif; ?>
                        <form method="post" class="delete-form mt-3">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>



<?php
require_once __DIR__ . '/../partials/admin_footer.php';
?>
