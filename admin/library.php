<?php
declare(strict_types=1);

$pageTitle = 'Research Library';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../partials/admin_header.php';

$errors = [];
$success = '';
$documents = [];

$pdo = null;
try {
    $pdo = get_db();
} catch (Throwable $e) {
    $errors[] = 'Database connection unavailable.';
}

$uploadDir = __DIR__ . '/../uploads/library';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $action = post_string('action');

    // Create Document
    if ($action === 'create') {
        $title = post_string('title');
        $docType = post_string('doc_type');
        $description = post_string('description');
        
        $validTypes = ['paper', 'synopsis', 'dataset'];

        if ($title === '') $errors[] = 'Title is required.';
        if (!in_array($docType, $validTypes, true)) $errors[] = 'Invalid document type.';
        
        if (!isset($_FILES['doc_file']) || $_FILES['doc_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'A file upload is required.';
        } else {
            $file = $_FILES['doc_file'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Validate extension based on type
            if ($docType === 'dataset') {
                $allowedExts = ['csv', 'xlsx'];
                if (!in_array($fileExt, $allowedExts)) {
                    $errors[] = 'Datasets must be CSV or XLSX files.';
                }
            } else {
                $allowedExts = ['pdf', 'doc', 'docx'];
                if (!in_array($fileExt, $allowedExts)) {
                    $errors[] = 'Research papers and synopses must be PDF, DOC, or DOCX files.';
                }
            }

            if (empty($errors)) {
                $fileName = bin2hex(random_bytes(8)) . '.' . $fileExt;
                $targetPath = $uploadDir . '/' . $fileName;

                if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $errors[] = 'Unable to save uploaded file.';
                } else {
                    $filePath = 'uploads/library/' . $fileName;
                    $fileSize = filesize($targetPath);
                    
                    try {
                        $stmt = $pdo->prepare('INSERT INTO research_documents (title, doc_type, description, file_path, file_size) VALUES (:title, :doc_type, :description, :file_path, :file_size)');
                        $stmt->execute([
                            'title' => $title,
                            'doc_type' => $docType,
                            'description' => $description,
                            'file_path' => $filePath,
                            'file_size' => $fileSize
                        ]);
                        $success = 'Document added to library.';
                    } catch (Throwable $e) {
                        $errors[] = 'Database error: ' . $e->getMessage();
                    }
                }
            }
        }
    }

    // Update Document
    if ($action === 'update') {
        $id = post_int('id');
        $title = post_string('title');
        $docType = post_string('doc_type');
        $description = post_string('description');
        
        if ($id > 0 && $title !== '') {
            $stmt = $pdo->prepare('SELECT file_path FROM research_documents WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $existingDoc = $stmt->fetch();
            
            if ($existingDoc) {
                $filePath = $existingDoc['file_path'];
                $fileSizeQuery = "";
                $params = [
                    'title' => $title,
                    'doc_type' => $docType,
                    'description' => $description,
                    'id' => $id
                ];
                
                // Handle file replacement
                if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['doc_file'];
                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if ($docType === 'dataset') {
                        $allowedExts = ['csv', 'xlsx'];
                        if (!in_array($fileExt, $allowedExts)) $errors[] = 'Datasets must be CSV or XLSX.';
                    } else {
                        $allowedExts = ['pdf', 'doc', 'docx'];
                        if (!in_array($fileExt, $allowedExts)) $errors[] = 'Papers must be PDF, DOC, or DOCX.';
                    }
                    
                    if (empty($errors)) {
                        $fileName = bin2hex(random_bytes(8)) . '.' . $fileExt;
                        $targetPath = $uploadDir . '/' . $fileName;
                        
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            // Delete old file
                            if (!empty($existingDoc['file_path']) && is_file(__DIR__ . '/../' . $existingDoc['file_path'])) {
                                unlink(__DIR__ . '/../' . $existingDoc['file_path']);
                            }
                            $filePath = 'uploads/library/' . $fileName;
                            $fileSizeQuery = ", file_path = :file_path, file_size = :file_size";
                            $params['file_path'] = $filePath;
                            $params['file_size'] = filesize($targetPath);
                        } else {
                            $errors[] = 'Unable to upload new file.';
                        }
                    }
                }
                
                if (empty($errors)) {
                    $updateStmt = $pdo->prepare("UPDATE research_documents SET title = :title, doc_type = :doc_type, description = :description $fileSizeQuery WHERE id = :id");
                    $updateStmt->execute($params);
                    $success = 'Document updated successfully.';
                }
            }
        }
    }

    // Delete Document
    if ($action === 'delete') {
        $id = post_int('id');
        if ($id > 0) {
            $stmt = $pdo->prepare('SELECT file_path FROM research_documents WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $doc = $stmt->fetch();

            if ($doc && !empty($doc['file_path'])) {
                $path = __DIR__ . '/../' . $doc['file_path'];
                if (is_file($path)) unlink($path);
            }

            $deleteStmt = $pdo->prepare('DELETE FROM research_documents WHERE id = :id');
            $deleteStmt->execute(['id' => $id]);
            $success = 'Document deleted from library.';
        }
    }
}

if ($pdo) {
    try {
        $documents = $pdo->query('SELECT id, title, doc_type, description, file_path, file_size, created_at FROM research_documents ORDER BY created_at DESC')->fetchAll();
    } catch (Throwable $e) {
        $errors[] = 'Documents table not found.';
        $documents = [];
    }
}
?>

<div class="glass-panel">
    <h5><i class="fa-solid fa-book"></i> Research Library Management</h5>
    <p class="muted">Upload and manage research papers, synopsis documents, and datasets. Support for PDF, DOCX, CSV, and XLSX.</p>

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

    <form method="post" enctype="multipart/form-data" class="mt-4 p-4 border rounded" style="background: rgba(255,255,255,0.02);">
        <h6>Add New Document</h6>
        <input type="hidden" name="action" value="create">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Type</label>
                <select name="doc_type" class="form-select" required>
                    <option value="paper">Research Paper</option>
                    <option value="synopsis">Synopsis</option>
                    <option value="dataset">Dataset</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label">Description (Optional)</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">File Upload</label>
                <input type="file" name="doc_file" class="form-control" accept=".pdf,.doc,.docx,.csv,.xlsx" required>
                <small class="muted">Allowed formats: PDF, DOC/DOCX (Papers/Synopsis) | CSV, XLSX (Datasets)</small>
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
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>File</th>
                        <th>Uploaded</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td>
                                <strong><?php echo e($doc['title']); ?></strong>
                                <?php if(!empty($doc['description'])): ?>
                                    <div class="small muted text-truncate" style="max-width: 250px;" title="<?php echo e($doc['description']); ?>">
                                        <?php echo e($doc['description']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo e(ucfirst($doc['doc_type'])); ?></span></td>
                            <td>
                                <?php if (!empty($doc['file_path'])): ?>
                                    <a href="../<?php echo e($doc['file_path']); ?>" class="btn btn-sm btn-outline-primary" target="_blank" download>
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                <?php else: ?>
                                    <span class="text-danger">File Missing</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($doc['created_at'])); ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $doc['id']; ?>">
                                    Edit
                                </button>
                                <form method="post" class="d-inline delete-form" onsubmit="return confirm('Delete this document?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Render Modals Outside the Table -->
        <?php foreach ($documents as $doc): ?>
            <div class="modal fade" id="editModal<?php echo $doc['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-dark">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo e($doc['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="doc_type" class="form-select" required>
                                        <option value="paper" <?php echo $doc['doc_type'] === 'paper' ? 'selected' : ''; ?>>Research Paper</option>
                                        <option value="synopsis" <?php echo $doc['doc_type'] === 'synopsis' ? 'selected' : ''; ?>>Synopsis</option>
                                        <option value="dataset" <?php echo $doc['doc_type'] === 'dataset' ? 'selected' : ''; ?>>Dataset</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2"><?php echo e((string)$doc['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Replace File (Optional)</label>
                                    <input type="file" name="doc_file" class="form-control" accept=".pdf,.doc,.docx,.csv,.xlsx">
                                    <small class="text-muted">Leave empty to keep existing file.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../partials/admin_footer.php';
?>
