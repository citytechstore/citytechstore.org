<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Listing</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h1>Product Listing</h1>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Price</th>
        <th scope="col">Quantity</th>
        <th scope="col">Manufacturer</th>
        <th scope="col">Category</th>
        <th scope="col">Uploaded By</th>
        <th scope="col">Created At</th>
      </tr>
    </thead>
    <tbody>
      <!-- Populate table rows dynamically with data from the database -->
      <!-- Example: -->
      <tr>
        <th scope="row">1</th>
        <td>Product 1</td>
        <td>Description of Product 1</td>
        <td>$20.00</td>
        <td>100</td>
        <td>Manufacturer 1</td>
        <td>Category 1</td>
        <td>Admin</td>
        <td>2024-05-21 10:30:00</td>
      </tr>
      <!-- Add more rows as needed -->
    </tbody>
  </table>
</div>

</body>
</html>
