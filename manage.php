<?php

session_start();

include '../config/database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../auth/loginregister.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($user_query);

$destinations = mysqli_query(
    $conn,
    "SELECT * FROM destinations ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>Manage Destinasi</title>

  <link
    rel="stylesheet"
    href="../assets/css/global.css">

  <link
    rel="stylesheet"
    href="../assets/css/admin.css">

  <script src="https://unpkg.com/lucide@latest"></script>

  <style>

    .manage-field select{
      appearance:none;
      -webkit-appearance:none;
      -moz-appearance:none;

      cursor:pointer;

      background:
        linear-gradient(45deg, transparent 50%, #39ff78 50%),
        linear-gradient(135deg, #39ff78 50%, transparent 50%);

      background-position:
        calc(100% - 22px) calc(50% - 3px),
        calc(100% - 16px) calc(50% - 3px);

      background-size:6px 6px;
      background-repeat:no-repeat;
    }

    .manage-field select option{
      background:#0f1713;
      color:#ffffff;
    }

    .admin-main{
      padding:48px 56px !important;
    }

    .admin-topbar{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:30px;
      margin-bottom:42px;
    }

    .admin-title{
      max-width:500px;
    }

    .admin-title h1{
      font-size:3rem;
      line-height:1.1;
      margin-bottom:14px;
    }

    .admin-title p{
      color:rgba(232,240,235,.58);
      line-height:1.8;
      font-size:.95rem;
    }

    .admin-user{
      display:flex;
      align-items:center;
      gap:14px;
      margin-top:6px;
    }

    .admin-user p{
      color:rgba(232,240,235,.58);
    }

    .manage-form{
      width:100%;
      max-width:1100px;

      padding:38px;

      border-radius:32px;

      background:rgba(12,18,14,.82);

      border:1px solid rgba(255,255,255,.08);

      margin-bottom:42px;
    }

    .manage-grid{
      display:grid;

      grid-template-columns:repeat(2,minmax(320px,1fr));

      gap:26px;
    }

    .manage-field{
      display:flex;
      flex-direction:column;
      gap:10px;
    }

    .manage-field label{
      color:rgba(232,240,235,.68);
      font-size:.9rem;
      font-weight:600;
    }

    .manage-field input,
    .manage-field select,
    .manage-field textarea{
      width:100%;

      border:none;
      outline:none;

      background:rgba(255,255,255,.03);

      border:1px solid rgba(255,255,255,.08);

      border-radius:18px;

      color:#fff;

      font-size:.95rem;
    }

    .manage-field input,
    .manage-field select{
      height:60px;
      padding:0 20px;
    }

    .manage-field textarea{
      min-height:160px;
      resize:none;
      padding:18px 20px;
    }

    .manage-field input:focus,
    .manage-field select:focus,
    .manage-field textarea:focus{
      border-color:rgba(57,255,120,.35);
    }

    .full-span{
      grid-column:1 / -1;
    }

    .manage-form .btn-primary{
      width:260px;
      height:60px;
      border:none;
      border-radius:18px;
      font-size:.95rem;
      margin-top:18px;
    }

    .manage-table{
      width:100%;
      max-width:1200px;

      border-collapse:collapse;

      overflow:hidden;

      border-radius:28px;

      background:rgba(12,18,14,.82);

      border:1px solid rgba(255,255,255,.08);
    }

    .manage-table thead{
      background:rgba(255,255,255,.03);
    }

    .manage-table th{
      padding:22px;

      text-align:left;

      color:rgba(232,240,235,.48);

      font-size:.82rem;

      text-transform:uppercase;
    }

    .manage-table td{
      padding:22px;

      border-top:1px solid rgba(255,255,255,.05);

      color:rgba(232,240,235,.78);

      vertical-align:middle;
    }

    .manage-table tbody tr:hover{
      background:rgba(255,255,255,.02);
    }

    .manage-thumb{
      width:120px;
      height:80px;

      object-fit:cover;

      border-radius:16px;
    }

    .manage-actions{
      display:flex;
      align-items:center;
      gap:10px;
    }

    .btn-edit,
    .btn-delete{
      display:inline-flex;
      align-items:center;
      justify-content:center;

      padding:11px 16px;

      border-radius:12px;

      font-size:.82rem;
      font-weight:700;

      color:#fff;

      text-decoration:none;
    }

    .btn-edit{
      background:#4f46e5;
    }

    .btn-delete{
      background:#ef4444;
    }

    @media(max-width:1000px){

      .manage-grid{
        grid-template-columns:1fr;
      }

      .admin-topbar{
        flex-direction:column;
      }

    }

  </style>

</head>

<body class="admin-page">

<div class="admin-wrap">

  <aside class="admin-sidebar">

    <div class="admin-logo">
      Wandee Admin
    </div>

    <nav class="admin-menu">

      <a href="dashboard.php">
        <i data-lucide="layout-dashboard"></i>
        Dashboard
      </a>

      <a href="manage.php" class="active">
        <i data-lucide="map"></i>
        Manage Destinasi
      </a>

      <a href="profile.php">
        <i data-lucide="user"></i>
        Profil Saya
      </a>

      <a href="../process/logout.php">
        <i data-lucide="log-out"></i>
        Keluar
      </a>

    </nav>

  </aside>

  <main class="admin-main">

    <div class="admin-topbar">

      <div class="admin-title">

        <h1>
          Manage Destinasi
        </h1>

        <p>
          Kelola seluruh destinasi wisata Wandee dari dashboard admin.
        </p>

      </div>

      <div class="admin-user">

        <div class="admin-user-empty">
          <i data-lucide="user"></i>
        </div>

        <div>

          <strong>
            <?php echo $user['name']; ?>
          </strong>

          <p>
            <?php echo $user['email']; ?>
          </p>

        </div>

      </div>

    </div>

    <form
      action="../process/destination_process.php"
      method="POST"
      enctype="multipart/form-data"
      class="manage-form"
    >

      <input type="hidden" name="action" value="add">

      <div class="manage-grid">

        <div class="manage-field">
          <label>Nama Destinasi</label>
          <input type="text" name="title" required>
        </div>

        <div class="manage-field">
          <label>Lokasi</label>
          <input type="text" name="location" required>
        </div>

        <div class="manage-field">

          <label>Kategori</label>

          <select name="category" required>

            <option value="" selected disabled>
              Pilih Kategori
            </option>

            <option value="Gunung">
              Gunung
            </option>

            <option value="Pantai">
              Pantai
            </option>

            <option value="Air Terjun">
              Air Terjun
            </option>

            <option value="Kota">
              Kota
            </option>

          </select>

        </div>

        <div class="manage-field">
          <label>Harga</label>
          <input type="text" name="price" required>
        </div>

        <div class="manage-field">
          <label>Rating</label>
          <input type="number" step="0.1" name="rating" required>
        </div>

        <div class="manage-field full-span">

          <label>Deskripsi</label>

          <textarea
            name="description"
            placeholder="Masukkan deskripsi destinasi..."
          ></textarea>

        </div>

        <div class="manage-field full-span">

          <label>Upload Gambar</label>

          <input
            type="file"
            name="image"
            required
          >

        </div>

      </div>

      <button
        type="submit"
        class="btn-primary"
      >

        Tambah Destinasi

      </button>

    </form>

    <table class="manage-table">

      <thead>

        <tr>
          <th>Gambar</th>
          <th>Nama</th>
          <th>Kategori</th>
          <th>Lokasi</th>
          <th>Harga</th>
          <th>Aksi</th>
        </tr>

      </thead>

      <tbody>

      <?php while($row = mysqli_fetch_assoc($destinations)) : ?>

        <tr>

          <td>

            <img
              src="../assets/img/<?php echo $row['image']; ?>"
              class="manage-thumb"
            >

          </td>

          <td>
            <?php echo $row['title']; ?>
          </td>

          <td>
            <?php echo $row['category']; ?>
          </td>

          <td>
            <?php echo $row['location']; ?>
          </td>

          <td>
            <?php echo $row['price']; ?>
          </td>

          <td>

            <div class="manage-actions">

              <a
                href="edit_destination.php?id=<?php echo $row['id']; ?>"
                class="btn-edit"
              >
                Edit
              </a>

              <a
                href="../process/destination_process.php?delete=<?php echo $row['id']; ?>"
                class="btn-delete"
                onclick="return confirm('Yakin ingin menghapus destinasi ini?')"
              >
                Delete
              </a>

            </div>

          </td>

        </tr>

      <?php endwhile; ?>

      </tbody>

    </table>

  </main>

</div>

<script>
  lucide.createIcons();
</script>

</body>
</html>
