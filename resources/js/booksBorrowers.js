import ApexCharts from "apexcharts";

var books = eval(window.books);
var borrowers = eval(window.borrower);

var monthGraph = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nove",
    "Dec",
];

console.log("test: " + books);

var bookData = [];

var monthFromController = [];

var cumulativeUsers = [];

for (let i = 0; i < books.length; i++) {
    bookData.push(books[i].count);
    monthFromController.push(monthGraph[books[i].month - 1]);
    cumulativeUsers.push(borrowers[i]);
}

console.log(bookData);
console.log(monthFromController);
console.log(cumulativeUsers);

var options = {
    series: [
        {
            name: "Books",
            data: bookData,
        },
        {
            name: "Borrowers",
            data: cumulativeUsers,
        },
    ],
    chart: {
        height: 450,
        type: "bar",
        dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
        },
        toolbar: {
            show: true,
            offsetY: -20,
            tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true,
            },
        },
        zoom: {
            enabled: true,
        },
    },
    colors: ["#48523e", "#c5b32a"],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Books and Borrowers Graph",
        align: "left",
        offsetY: 10,
    },
    grid: {
        borderColor: "#e7e7e7",
        row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
        },
        padding: {
            left: 30, // or whatever value that works
            right: 30, // or whatever value that works
        },
    },
    markers: {
        size: 1,
    },
    xaxis: {
        categories: monthFromController,
        tickPlacement: "on",
        title: {
            text: "Month",
        },
    },
    yaxis: {
        title: {
            text: "Number of Students",
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },
};

var booksBorrowersChart = new ApexCharts(
    document.querySelector("#booksBorrowers"),
    options
);

booksBorrowersChart.render();
