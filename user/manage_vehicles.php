<?php
session_start();
include('../config/function.php');
include('include/header.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];

// Fetch vehicles of logged-in user
$sql = "SELECT ID, VehicleModel, VehicleCategory, VehicleCompanyname, Color, RegistrationNumber FROM tblvehicle WHERE UserId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <h2 class="mb-4">My Vehicles</h2>

    <div id="message-container"></div>

    <?php if ($result->num_rows === 0): ?>
      <p>You have not added any vehicles yet.</p>
      <a href="add_vehicle.php" class="btn btn-primary">Add Vehicle</a>
    <?php else: ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Model</th>
            <th>Category</th>
            <th>Company</th>
            <th>Color</th>
            <th>Registration Number</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="vehicle-table-body">
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= htmlspecialchars($row['ID']) ?>">
              <td><?= htmlspecialchars($row['VehicleModel']) ?></td>
              <td><?= htmlspecialchars($row['VehicleCategory']) ?></td>
              <td><?= htmlspecialchars($row['VehicleCompanyname']) ?></td>
              <td><?= htmlspecialchars($row['Color']) ?></td>
              <td><?= htmlspecialchars($row['RegistrationNumber']) ?></td>
              <td>
                <a href="edit_vehicle.php?id=<?= $row['ID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $row['ID'] ?>">Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <a href="add_vehicle.php" class="btn btn-primary">Add New Vehicle</a>
    <?php endif; ?>
  </div>
</div>

<script>
document.querySelectorAll('.btn-delete').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    e.preventDefault();

    const vehicleId = this.getAttribute('data-id');
    const row = this.closest('tr');

    fetch('delete_vehicle.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'vehicleId=' + encodeURIComponent(vehicleId)
    })
    .then(res => res.json())
    .then(data => {
      const messageContainer = document.getElementById('message-container');
      messageContainer.innerHTML = '';

      if (data.success) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'alert alert-success';
        messageDiv.textContent = data.message;
        messageContainer.appendChild(messageDiv);

        row.remove();

        setTimeout(() => {
          messageDiv.style.transition = "opacity 0.5s ease";
          messageDiv.style.opacity = '0';
          setTimeout(() => messageDiv.remove(), 500);
        }, 1700);
      } else {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = 'Error: ' + data.message;
        messageContainer.appendChild(errorDiv);
      }
    })
    .catch(err => {
      alert('Error: ' + err.message);
    });
  });
});
</script>

<?php
$stmt->close();
include('include/footer.php');
?>
