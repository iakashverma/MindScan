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

    if ($action === 'doc_create') {
        $docTitle = post_string('doc_title');
        $docType = post_string('doc_type');
        $docDescription = post_string('doc_description');
        $filePath = '';

        $validDocTypes = ['paper', 'synopsis', 'dataset'];

        if ($docTitle === '') {
            $errors[] = 'Document title is required.';
        }
        if (!in_array($docType, $validDocTypes, true)) {
            $errors[] = 'Invalid document type selected.';
        }
        if ($docDescription === '') {
            $errors[] = 'Document description is required.';
        }

        if (!isset($_FILES['doc_file']) || $_FILES['doc_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'A PDF upload is required.';
        } else {
            $file = $_FILES['doc_file'];
            $maxSize = 8 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                $errors[] = 'PDF files must be 8MB or smaller.';
            } else {
                $mime = '';
                if (class_exists('finfo')) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file['tmp_name']);
                }
                if ($mime === '' && function_exists('mime_content_type')) {
                    $mime = mime_content_type($file['tmp_name']);
                }

                $allowed = ['application/pdf', 'application/x-pdf'];
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Only PDF files are allowed.';
                } else {
                    $uploadDir = __DIR__ . '/../uploads/research_docs';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = bin2hex(random_bytes(8)) . '.pdf';
                    $targetPath = $uploadDir . '/' . $fileName;

                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $errors[] = 'Unable to save uploaded PDF.';
                    } else {
                        $filePath = 'uploads/research_docs/' . $fileName;
                    }
                }
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare('INSERT INTO research_documents (title, doc_type, description, file_path) VALUES (:title, :doc_type, :description, :file_path)');
                $stmt->execute([
                    'title' => $docTitle,
                    'doc_type' => $docType,
                    'description' => $docDescription,
                    'file_path' => $filePath,
                ]);
                $success = 'Research document added.';
            } catch (Throwable $e) {
                $errors[] = 'Unable to save the document. Please ensure the research_documents table exists.';
            }
        }
    }

    if ($action === 'doc_delete') {
        $docId = post_int('doc_id');
        if ($docId > 0) {
            try {
                $stmt = $pdo->prepare('SELECT file_path FROM research_documents WHERE id = :id');
                $stmt->execute(['id' => $docId]);
                $doc = $stmt->fetch();

                if ($doc && !empty($doc['file_path'])) {
                    $path = __DIR__ . '/../' . $doc['file_path'];
                    if (is_file($path)) {
                        unlink($path);
                    }
                }

                $deleteStmt = $pdo->prepare('DELETE FROM research_documents WHERE id = :id');
                $deleteStmt->execute(['id' => $docId]);
                $success = 'Research document deleted.';
            } catch (Throwable $e) {
                $errors[] = 'Unable to delete the document. Please verify the research_documents table.';
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

if ($pdo) {
    try {
        $documents = $pdo->query('SELECT id, title, doc_type, description, file_path, created_at FROM research_documents ORDER BY created_at DESC')->fetchAll();
    } catch (Throwable $e) {
        $errors[] = 'Research documents table not found. Run the latest database setup script.';
        $documents = [];
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

<div class="glass-panel mt-4">
    <h5>Upload Research Documents</h5>
    <p class="muted">Upload PDF research papers, synopsis documents, or datasets for the public dashboard.</p>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="doc_create">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Document Title</label>
                <input type="text" name="doc_title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Document Type</label>
                <select name="doc_type" class="form-select" required>
                    <option value="paper">Research Paper</option>
                    <option value="synopsis">Synopsis</option>
                    <option value="dataset">Dataset</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="doc_description" class="form-control" rows="3" placeholder="Short summary shown on the home page." required></textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">PDF Upload</label>
                <input type="file" name="doc_file" class="form-control" accept="application/pdf" required>
                <small class="muted">PDF only. Max size 8MB.</small>
            </div>
        </div>
        <button class="btn btn-gradient mt-3" type="submit">Upload Document</button>
    </form>
</div>

<div class="glass-panel mt-4">
    <h5>Available Documents</h5>
    <?php if (empty($documents)): ?>
        <p class="muted">No documents uploaded yet.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($documents as $doc): ?>
                <div class="col-md-6">
                    <div class="glass-card">
                        <div class="d-flex justify-content-between">
                            <strong><?php echo e($doc['title']); ?></strong>
                            <span class="muted"><?php echo e(ucfirst((string)$doc['doc_type'])); ?></span>
                        </div>
                        <p class="mt-2 mb-2 muted"><?php echo nl2br(e((string)$doc['description'])); ?></p>
                        <?php if (!empty($doc['file_path'])): ?>
                            <div class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-light" href="<?php echo e($doc['file_path']); ?>" target="_blank" rel="noopener">View</a>
                                <a class="btn btn-sm btn-gradient" href="<?php echo e($doc['file_path']); ?>" download>Download</a>
                            </div>
                        <?php else: ?>
                            <p class="muted">File missing.</p>
                        <?php endif; ?>
                        <form method="post" class="delete-form mt-3">
                            <input type="hidden" name="action" value="doc_delete">
                            <input type="hidden" name="doc_id" value="<?php echo (int)$doc['id']; ?>">
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
