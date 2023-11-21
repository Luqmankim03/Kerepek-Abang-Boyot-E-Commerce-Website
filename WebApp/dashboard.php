<?php
$host = "localhost:3308";
$username = "root";
$password = "";
$database = "kerepek";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to get the total products sold
$totalProductsSoldQuery = "SELECT SUM(Quantity) AS totalQuantity FROM `order`";
$result = mysqli_query($conn, $totalProductsSoldQuery);

if (!$result) {
    die("Error in totalProductsSoldQuery: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $totalProductsSold = $row['totalQuantity'];
} else {
    $totalProductsSold = 0;
}

// Query to get the total earnings
$totalEarningsQuery = "SELECT SUM(Total) AS totalEarnings FROM `order`";
$result = mysqli_query($conn, $totalEarningsQuery);

if (!$result) {
    die("Error in totalEarningsQuery: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $totalEarnings = $row['totalEarnings'];
} else {
    $totalEarnings = 0;
}

// Query to get monthly sales data based on Timestamp
$monthlySalesQuery = "SELECT DATE_FORMAT(`Timestamp`, '%b %Y') AS month, SUM(Total) AS totalSales
    FROM `order`
    GROUP BY month
    ORDER BY `Timestamp`";
$monthlySalesResult = mysqli_query($conn, $monthlySalesQuery);

$months = [];
$salesData = [];

// Initialize an array to hold sales data for all months
$allMonthsData = array_fill(0, 12, 0);

if ($monthlySalesResult && mysqli_num_rows($monthlySalesResult) > 0) {
    while ($row = mysqli_fetch_assoc($monthlySalesResult)) {
        $monthName = $row['month'];
        $sales = $row['totalSales'];

        // Extract the month and year from the date format
        $dateParts = explode(" ", $monthName);
        $month = $dateParts[0];
        $year = $dateParts[1];

        // Array of all 12 months
        $allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Find the index of the month in the allMonthsData array
        $monthIndex = array_search($month, $allMonths);

        if ($monthIndex !== false) {
            // Add the sales data to the corresponding month
            $allMonthsData[$monthIndex] = $sales;
        }
    }

    // Populate the $months and $salesData arrays from $allMonthsData
    $months = $allMonths;
    $salesData = $allMonthsData;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
</head>

<body>
    <div class="header">
        <a href="admin.php" class="back-button">Go Back</a>
        <div class="header-buttons">
            <form action="reset_data.php" method="post">
                <button type="submit" name="reset" class="reset-button">Reset Data</button>
            </form>
            <button id="download-button" class="download-button">Download</button>
        </div>
    </div>
    <div class="container">
        <div class="sales-info">
            <h2>Sales Summary</h2>
            <p class="total-sold">Total Products Sold:
                <?php echo $totalProductsSold; ?>
            </p>
            <p class="total-earnings">Total Earnings:
                <?php echo 'RM' . number_format($totalEarnings, 2); ?>
            </p>
        </div>
        <div class="bar-chart">
            <canvas id="sales-chart"></canvas>
        </div>
    </div>
    <script>
        const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const displayedMonths = <?php echo json_encode($months); ?>;
        const monthlySales = <?php echo json_encode($salesData); ?>;
        const maxMonthlySales = 500;

        const labels = allMonths;

        const data = Array(allMonths.length).fill(0);

        displayedMonths.forEach((month, index) => {
            const monthIndex = labels.indexOf(month);
            if (monthIndex !== -1) {
                data[monthIndex] = Math.min(monthlySales[index], maxMonthlySales);
            }
        });

        const ctx = document.getElementById('sales-chart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Sales (RM)',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: maxMonthlySales
                    }
                }
            }
        });

        // Function to download the page as an image
        const downloadButton = document.getElementById('download-button');
        downloadButton.addEventListener('click', () => {
            // Capture the content of the container (entire page)
            domtoimage.toBlob(document.body)
                .then(function (blob) {
                    window.saveAs(blob, 'sales_summary.png');
                });
        });
    </script>
</body>

</html>