import ApexCharts from "apexcharts";

var departmentGraphData = document.getElementById("departmentGraphData");

var userDepartment = departmentGraphData.getAttribute("data-user-department");
var months = JSON.parse(departmentGraphData.getAttribute("data-months"));

var strDepartmentGraphData = userDepartment;
var cleanStrDepartmentGraphData = strDepartmentGraphData.replace(
    /[^0-9,]/g,
    ""
);
var arrayDepartmentGraphData = cleanStrDepartmentGraphData
    .split(",")
    .map(Number);

var strMonths = months;
var cleanStrMonths = strMonths.replace(/[^0-9,]/g, "");
var arrayMonth = cleanStrMonths.split(",").map(Number);

console.log(arrayMonth.length);

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

var shs = [];
var cas = [];
var cea = [];
var cma = [];
var cela = [];
var chs = [];
var cite = [];
var ccje = [];

for (var i = 0; i < arrayDepartmentGraphData.length; i++) {
    switch (i % 8) {
        case 0:
            shs.push(arrayDepartmentGraphData[i]);
            break;
        case 1:
            cas.push(arrayDepartmentGraphData[i]);
            break;
        case 2:
            cea.push(arrayDepartmentGraphData[i]);
            break;
        case 3:
            cma.push(arrayDepartmentGraphData[i]);
            break;
        case 4:
            cela.push(arrayDepartmentGraphData[i]);
            break;
        case 5:
            chs.push(arrayDepartmentGraphData[i]);
            break;
        case 6:
            cite.push(arrayDepartmentGraphData[i]);
            break;
        case 7:
            ccje.push(arrayDepartmentGraphData[i]);
            break;
    }
}

var monthFromController = [];

// var cumulativeUsers = [];

for (let i = 0; i < arrayMonth.length; i++) {
    monthFromController.push(monthGraph[arrayMonth[i] - 1]);
}

// console.log(bookData);
console.log(monthFromController);
// console.log(cumulativeUsers);

var options = {
    series: [
        {
            name: "SHS",
            data: shs,
        },
        {
            name: "CAS",
            data: cas,
        },
        {
            name: "CEA",
            data: cea,
        },
        {
            name: "CMA",
            data: cma,
        },
        {
            name: "CELA",
            data: cela,
        },
        {
            name: "CHS",
            data: chs,
        },
        {
            name: "CITE",
            data: cite,
        },
        {
            name: "CCJE",
            data: ccje,
        },
    ],
    chart: {
        type: "bar",
        height: 450,
        stacked: true,
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
    colors: [
        "#48523e",
        "#49672f",
        "#690404",
        "#c5b32a",
        "#201cef",
        "#989899",
        "#000000",
        "#81a581",
    ],
    dataLabels: {
        enabled: true,
    },
    title: {
        text: "Per Department Graph",
        align: "left",
        offsetY: 10,
    },
    responsive: [
        {
            breakpoint: 480,
            options: {
                legend: {
                    position: "bottom",
                    offsetX: -10,
                    offsetY: 0,
                },
            },
        },
    ],
    plotOptions: {
        bar: {
            horizontal: false,
            borderRadius: 10,
            dataLabels: {
                total: {
                    enabled: true,
                    style: {
                        fontSize: "13px",
                        fontWeight: 900,
                    },
                },
            },
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
        position: "right",
        offsetY: 40,
    },
    fill: {
        opacity: 1,
    },
};

var departmentGraphChart = new ApexCharts(
    document.querySelector("#departmentGraph"),
    options
);
departmentGraphChart.render();
