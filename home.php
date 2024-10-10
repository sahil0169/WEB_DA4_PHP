<?php
// Start the session to store inventory data across requests
session_start();

// Initialize the inventory array if it's not already set in the session
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [];
}

// Function to add a new item to the inventory
function addItem(&$inventory, $name, $category, $price, $quantity, $itemCode) {
    $item = [
        'name' => $name,
        'category' => $category,
        'price' => $price,
        'quantity' => $quantity,
        'itemCode' => $itemCode
    ];
    $inventory[] = $item;
}

// Function to display all items in the inventory
function displayInventory($inventory) {
    if (empty($inventory)) {
        echo "<p class='no-items'>Inventory is empty.</p>";
    } else {
        echo "<table class='inventory-table'>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Item Code</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($inventory as $item) {
            echo "<tr>
                    <td>{$item['name']}</td>
                    <td>{$item['category']}</td>
                    <td>\${$item['price']}</td>
                    <td>{$item['quantity']}</td>
                    <td>{$item['itemCode']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    }
}

// Function to search for an item by name
function searchItem($inventory, $searchTerm) {
    $results = [];
    foreach ($inventory as $item) {
        if (stripos($item['name'], $searchTerm) !== false) {
            $results[] = $item;
        }
    }
    return $results;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add item form
    if (isset($_POST['addItem'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = (float)$_POST['price'];
        $quantity = (int)$_POST['quantity'];
        $itemCode = $_POST['itemCode'];
        
        // Add the item to the inventory stored in session
        addItem($_SESSION['inventory'], $name, $category, $price, $quantity, $itemCode);
    }

    // Search item form
    if (isset($_POST['searchItem'])) {
        $searchTerm = $_POST['searchTerm'];
        $searchResults = searchItem($_SESSION['inventory'], $searchTerm);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supermarket Inventory Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        h2 {
            color: #5A67D8;
            text-align: center;
            margin-top: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        form label {
            font-weight: bold;
        }
        form input[type="text"], form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="submit"] {
            background-color: #5A67D8;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form input[type="submit"]:hover {
            background-color: #434190;
        }
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .inventory-table th {
            background-color: #5A67D8;
            color: white;
        }
        .inventory-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .inventory-table tr:hover {
            background-color: #ddd;
        }
        .no-items {
            text-align: center;
            padding: 20px;
            color: #888;
            font-style: italic;
        }
        .search-results {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2> SAHIL AGARWAL SUPERMARKET</h2>
    <h2>Add a New Item</h2>
    <form method="POST">
        <label for="name">Item Name:</label>
        <input type="text" name="name" required>

        <label for="category">Category:</label>
        <input type="text" name="category" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <label for="itemCode">Item Code:</label>
        <input type="text" name="itemCode" required>

        <input type="submit" name="addItem" value="Add Item">
    </form>

    <h2>Inventory List</h2>
    <?php
    // Display the inventory stored in session
    displayInventory($_SESSION['inventory']);
    ?>

    <h2>Search for an Item</h2>
    <form method="POST">
        <label for="searchTerm">Search by Item Name:</label>
        <input type="text" name="searchTerm" required>
        <input type="submit" name="searchItem" value="Search">
    </form>

    <?php
    // Display search results, if available
    if (isset($searchResults)) {
        echo "<div class='search-results'><h3>Search Results:</h3>";
        if (empty($searchResults)) {
            echo "<p>No items found for '{$searchTerm}'.</p>";
        } else {
            echo "<table class='inventory-table'>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Item Code</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($searchResults as $item) {
                echo "<tr>
                        <td>{$item['name']}</td>
                        <td>{$item['category']}</td>
                        <td>\${$item['price']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['itemCode']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        }
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
