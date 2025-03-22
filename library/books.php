<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Info - Library Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .btn-info-custom {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-info-custom:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .navbar-nav {
            margin-left: auto;
            margin-right: auto;
        }
        .navbar-nav .nav-item {
            margin-left: 10px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Library Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="books.php">Books Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php">Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transactions.php">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">Reports</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Books Info</h2>
    <div class="d-flex justify-content-center mb-3">
        <a href="add.php" class="btn btn-success">Add New Book</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Barcode</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="bookTable">
            <?php
            require_once "Book.php";
            $book = new Book();
            $books = $book->getBooks();

            if (empty($books)): ?>
                <tr>
                    <td colspan="7" class="text-center">No books found</td>
                </tr>
            <?php else:
                foreach ($books as $b): ?>
                    <tr>
                        <td><?= $b['id']; ?></td>
                        <td><?= htmlspecialchars($b['title']); ?></td>
                        <td><?= htmlspecialchars($b['author']); ?></td>
                        <td><?= $b['published_year']; ?></td>
                        <td><?= htmlspecialchars($b['barcode']); ?></td>
                        <td><?= htmlspecialchars($b['stat']); ?></td>
                        <td>
                            <a href="edit.php?id=<?= $b['id']; ?>" class="btn btn-warning">Edit</a>
                            <button class="btn btn-danger delete-btn"
                                    data-id="<?= $b['id']; ?>"
                                    data-title="<?= htmlspecialchars($b['title']); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">Delete</button>
                        </td>
                    </tr>
                <?php endforeach;
            endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="bookTitle"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="delete.php">
                    <input type="hidden" name="id" id="deleteBookId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center p-3 bg-primary text-white mt-5">
    <p>Created By <strong>INCOGNITO</strong> &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            let query = $(this).val();
            $.ajax({
                url: "search.php",
                method: "POST",
                data: { query: query },
                success: function(data) {
                    $("#bookTable").html(data);
                }
            });
        });

        // Delete confirmation modal setup
        $(document).on("click", ".delete-btn", function() {
            let bookId = $(this).data("id");
            let bookTitle = $(this).data("title");

            $("#deleteBookId").val(bookId);
            $("#bookTitle").text(bookTitle);
        });
    });
</script>

</body>
</html>