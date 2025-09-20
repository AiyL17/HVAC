<?php
include 'config/ini.php';
$pdo = pdo_init();

// Handle add, update, delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_appliance_type'])) {
        $appliance_type_name = trim($_POST['appliance_type_name']);
        if ($appliance_type_name !== '') {
            $stmt = $pdo->prepare("INSERT INTO appliances_type (appliances_type_name) VALUES (?)");
            $stmt->execute([$appliance_type_name]);
        }
    } elseif (isset($_POST['edit_appliance_type'])) {
        $id = $_POST['appliances_type_id'];
        $appliance_type_name = trim($_POST['appliance_type_name']);
        if ($appliance_type_name !== '') {
            $stmt = $pdo->prepare("UPDATE appliances_type SET appliances_type_name=? WHERE appliances_type_id=?");
            $stmt->execute([$appliance_type_name, $id]);
        }
    } elseif (isset($_POST['delete_appliance_type'])) {
        $id = $_POST['appliances_type_id'];
        $stmt = $pdo->prepare("DELETE FROM appliances_type WHERE appliances_type_id=?");
        $stmt->execute([$id]);
    }
    header('Location: appliance-type-management.php');
    exit();
}

// Fetch all appliance types
$applianceTypes = $pdo->query("SELECT * FROM appliances_type ORDER BY appliances_type_name")->fetchAll(PDO::FETCH_OBJ);
?>
<div class="container mt-4">
    <h3 class="mb-4">Appliance Types Management</h3>
    <div class="card p-3 round_md mb-4">
        <form method="post" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label">Appliance Type Name</label>
                <input type="text" name="appliance_type_name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <button type="submit" name="add_appliance_type" class="btn btn-primary w-100">Add Appliance Type</button>
            </div>
        </form>
    </div>
    <div class="card p-3 round_md">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Appliance Type Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applianceTypes as $appliance): ?>
                        <tr>
                            <td><?= htmlspecialchars($appliance->appliances_type_name) ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editApplianceModal<?= $appliance->appliances_type_id ?>">Edit</button>
                                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this appliance type?');">
                                    <input type="hidden" name="appliances_type_id" value="<?= $appliance->appliances_type_id ?>">
                                    <button type="submit" name="delete_appliance_type" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editApplianceModal<?= $appliance->appliances_type_id ?>" tabindex="-1" aria-labelledby="editApplianceModalLabel<?= $appliance->appliances_type_id ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content round_md">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editApplianceModalLabel<?= $appliance->appliances_type_id ?>">Edit Appliance Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="appliances_type_id" value="<?= $appliance->appliances_type_id ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Appliance Type Name</label>
                                        <input type="text" name="appliance_type_name" class="form-control" value="<?= htmlspecialchars($appliance->appliances_type_name) ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="edit_appliance_type" class="btn btn-primary">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 